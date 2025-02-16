<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
    public function showCategories()
    {
        $categories = Category::withCount(['children', 'ads'])
            ->with('parent')
            ->orderBy('parent_category_id', 'asc')
            ->orderBy('name', 'asc')
            ->get();
        return view('admin.categories.categories', compact('categories'));
    }
    public function showCategoryCreatePage()
    {
        $categories = Category::all();
        return view('admin.categories.createCategory', compact('categories'));
    }
    public function deleteCategory(Category $category)
    {
        $category->delete();

        return redirect()->route('admin.categories')
            ->with('success', 'Категория успешно удалена');
    }
    public function createCategory(Request $request)
    {
        $validated = $request->validate([
            'parent_category_id' => 'nullable|exists:categories,id',
            'name' => 'required|string|max:255',
            'icon' => 'nullable|image|max:2048', // максимум 2MB
        ]);

        $category = Category::query()->create([
            'parent_category_id' =>  $validated['parent_category_id'],
            'name' => $validated['name'],
        ]);

        if ($request->hasFile('icon')) {
            $path = $request->file('icon')->store('categories', 'public');
            $category->icon = $path;
        }

        $category->save();

        return redirect()->route('admin.categories')
            ->with('success', 'Категория успешно создана');
    }
    public function showCategoryEditPage(Category $category)
    {
        // Получаем все категории кроме текущей и ее подкатегорий
        $categories = Category::whereNotIn('id', [
            $category->id, // исключаем текущую категорию
            ...$category->children()->pluck('id') // исключаем все подкатегории
        ])
            ->whereNull('parent_category_id') // только корневые
            ->with('children')  // подгружаем их прямых потомков
            ->orderBy('name')
            ->get();

        return view('admin.categories.editCategory', compact('category', 'categories'));
    }

    public function updateCategory(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'parent_category_id' => [
                'nullable',
                'exists:categories,id',
                // Проверяем что новый родитель не является потомком текущей категории
                function ($attribute, $value, $fail) use ($category) {
                    if ($value && $category->children()->pluck('id')->contains($value)) {
                        $fail('Нельзя выбрать подкатегорию в качестве родительской категории.');
                    }
                },
            ],
            'icon' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        try {
            // Обработка новой иконки если она загружена
            if ($request->hasFile('icon')) {
                // Удаляем старую иконку если она не является дефолтной
                if ($category->icon && $category->icon !== 'storage/categories/placeholder.jpg') {
                    Storage::disk('public')->delete($category->icon);
                }
                // Сохраняем новую иконку
                $path = $request->file('icon')->store('categories', 'public');
                $validated['icon'] = $path;
            }

            $category->update($validated);

            return redirect()
                ->route('admin.categories')
                ->with('success', 'Категория успешно обновлена');

        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Ошибка при обновлении категории: ' . $e->getMessage());
        }
    }
}
