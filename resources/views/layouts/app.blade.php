<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Happy Nest - Уютный маркет детских товаров</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/js/all.min.js"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <style>
        :root {
            --primary-color: #FF9494; /* Мягкий розовый */
            --secondary-color: #FFE3E1; /* Светло-розовый */
            --accent-color: #94B3FD; /* Нежно-голубой */
            --bg-color: #FFF5E4; /* Пастельный бежевый */
            --primary-dark: #2e2a2a;
        }
        .category-item {
            transition: all 0.2s ease;
        }

        .category-item:hover {
            background-color: var(--bg-color);
        }


        /* Анимация для стрелки */
        .transform {
            transition: transform 0.2s ease;
        }

        .rotate-90 {
            transform: rotate(90deg);
        }

        .hero-pattern {
            background-image: url("data:image/svg+xml,%3Csvg width='52' height='26' viewBox='0 0 52 26' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ff9494' fill-opacity='0.1'%3E%3Cpath d='M10 10c0-2.21-1.79-4-4-4-3.314 0-6-2.686-6-6h2c0 2.21 1.79 4 4 4 3.314 0 6 2.686 6 6 0 2.21 1.79 4 4 4 3.314 0 6 2.686 6 6 0 2.21 1.79 4 4 4v2c-3.314 0-6-2.686-6-6 0-2.21-1.79-4-4-4-3.314 0-6-2.686-6-6zm25.464-1.95l8.486 8.486-1.414 1.414-8.486-8.486 1.414-1.414z' /%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        }

        .category-card {
            transition: transform 0.2s;
        }

        .category-card:hover {
            transform: translateY(-5px);
        }
        .category-img{
            border-radius: 50%;
        }
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .card-transition {
            transition: all 0.3s ease;
        }

        .card-transition:hover {
            transform: translateY(-2px);
        }
        .adaptive-grid {
            display: grid;
            gap: 0.75rem;
            grid-template-columns: repeat(1, 1fr);
        }

        @media (min-width: 640px) {
            .adaptive-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (min-width: 768px) {
            .adaptive-grid {
                grid-template-columns: repeat(3, 1fr);
            }
        }

        @media (min-width: 1024px) {
            .adaptive-grid {
                grid-template-columns: repeat(6, 1fr);
            }
        }

        .modal-backdrop {
            position: fixed;
            top: 0;
            right: 0;
            bottom: 0;
            left: 0;
            background-color: rgba(0, 0, 0, 0.5);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 9998;
            animation: fadeIn 0.2s ease-out;
        }

        .modal-content {
            animation: slideIn 0.2s ease-out;
            background: white;
            padding: 2rem;
            border-radius: 1rem;
            width: 100%;
            max-width: 28rem;
            margin: 1rem;
            z-index: 9999;
            box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
        }

        .form-label {
            display: block;
            font-size: 0.875rem;
            font-weight: 500;
            color: #374151;
            margin-bottom: 0.5rem;
        }

        .form-input {
            width: 100%;
            padding: 0.625rem 0.875rem;
            border: 1px solid #E5E7EB;
            border-radius: 0.5rem;
            background-color: #F9FAFB;
            color: #1F2937;
            font-size: 0.875rem;
            transition: all 0.15s ease;
        }

        .form-input:focus {
            outline: none;
            border-color: var(--primary-color);
            background-color: white;
            box-shadow: 0 0 0 3px rgba(var(--primary-color), 0.1);
        }

        .form-input::placeholder {
            color: #9CA3AF;
        }

        .form-error {
            color: #DC2626;
            font-size: 0.875rem;
            margin-top: 0.25rem;
        }

        .modal-title {
            font-size: 1.5rem;
            font-weight: 600;
            color: #1F2937;
        }

        .modal-button {
            width: 100%;
            padding: 0.625rem;
            border-radius: 0.5rem;
            font-weight: 500;
            transition: all 0.15s ease;
        }

        .modal-button-primary {
            background-color: var(--primary-color);
            color: white;
            border: none;
        }

        .modal-button-primary:hover {
            background-color: var(--primary-dark);
        }

        .modal-link {
            color: var(--primary-color);
            font-weight: 500;
            transition: color 0.15s ease;
        }

        .modal-link:hover {
            color: var(--primary-dark);
        }

        [x-cloak] {
            display: none !important;
        }

        body.modal-open {
            overflow: hidden;
        }
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>
<body class="{{ auth()->check() ? 'user-authenticated' : '' }} bg-[var(--bg-color)]">
<!-- Главное меню -->
<header class="bg-white shadow-md z-10 relative">
    <div class="px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
            <!-- Левая часть с логотипом и кнопкой категорий -->
            <div class="flex items-center space-x-4">
                <!-- Логотип -->
                <a href="{{ route('home') }}" class="flex items-center space-x-2">

                    <span class="text-3xl font-bold text-[var(--primary-color)]">🪺 Happy Nest</span>
                </a>
            </div>
            <div class="flex items-center space-x-4">
                <!-- Кнопка категорий -->
                <a href="{{ route('categories') }}" class="flex items-center space-x-2">
                    <span class="text-lg font-normal text-[var(--primary-color)]">Категории</span>
                </a>
            </div>

            <!-- Центральная часть с поиском -->
            <div class="flex-1 max-w-2xl mx-8">
                <form action="{{ route('search') }}" method="GET">
                    <div class="relative">
                        <!-- Поисковая строка -->
                        <div class="flex items-center border border-[var(--secondary-color)] rounded-full bg-white overflow-hidden">
                            <div class="px-3 text-gray-400">
                                <i class="fas fa-search"></i>
                            </div>
                            <input type="text"
                                   name="search"
                                   id="search"
                                   value="{{ request('search') }}"
                                   class="w-full py-2 px-2 border-0 focus:outline-none focus:ring-0"
                                   placeholder="Введите название товара...">
                        </div>
                    </div>
                </form>
            </div>

            <!-- Правая часть с действиями -->
            <div class="flex items-center space-x-4">
                @auth
                    <a href="{{ route('create_ad') }}" data-auth-required
                       class="flex items-center px-4 py-2 text-sm font-medium text-white bg-[var(--primary-color)] rounded-full hover:bg-[var(--primary-dark)] transition duration-150 ease-in-out">
                        <i class="fas fa-plus mr-2"></i>
                        Разместить объявление
                    </a>

                    <!-- Профиль -->
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open"
                                @click.away="open = false"
                                class="flex items-center text-gray-700 hover:text-[var(--primary-color)]">
                            @if(auth()->user()->photo)
                                <img src="{{ auth()->user()->photo ? asset('storage/' . auth()->user()->photo) : asset('avatars/default.jpg') }}"
                                     alt="{{ auth()->user()->name }}"
                                     class="w-8 h-8 rounded-full object-cover">
                            @else
                                <div class="w-8 h-8 rounded-full bg-[var(--accent-color)] flex items-center justify-center text-white font-medium">
                                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                </div>
                            @endif
                            <i class="fas fa-chevron-down ml-2"></i>
                        </button>

                        <!-- Выпадающее меню профиля -->
                        <div x-show="open"
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 transform scale-95"
                             x-transition:enter-end="opacity-100 transform scale-100"
                             x-transition:leave="transition ease-in duration-150"
                             x-transition:leave-start="opacity-100 transform scale-100"
                             x-transition:leave-end="opacity-0 transform scale-95"
                             class="absolute right-0 mt-2 bg-white rounded-lg py-2 z-50" style="width: 200px; box-shadow: 0 1px 3px 0 rgb(0 0 0 / 50%);">
                            <a href="{{ route('profile') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-[var(--bg-color)]" data-auth-required>
                                <i class="fas fa-user mr-2"></i>
                                Профиль
                            </a>
                            <a href="{{ route('my_ads') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-[var(--bg-color)]" data-auth-required>
                                <i class="fas fa-list mr-2"></i>
                                Мои объявления
                            </a>
                            <a href="{{ route('favourites') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-[var(--bg-color)]" data-auth-required>
                                <i class="fas fa-heart mr-2"></i>
                                Избранное
                            </a>
                            <a href="{{ route('my_chats') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-[var(--bg-color)]" data-auth-required>
                                <i class="fas fa-comment mr-2"></i>
                                Сообщения
                            </a>
                            <div class="border-t border-gray-100 my-1"></div>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-[var(--bg-color)]">
                                    <i class="fas fa-sign-out-alt mr-2"></i>
                                    Выйти
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    <!-- Модалки логина и регистрации -->
                    <div x-data="{ loginModal: false, registerModal: false }">
                        <!-- Кнопки -->
                        <button type="button"
                                @click="$store.modal.openLogin()"
                                class="text-[var(--primary-color)] hover:text-[var(--primary-dark)]">
                            Войти
                        </button>
                        <button type="button"
                                @click="$store.modal.openRegister()"
                                class="px-4 py-2 text-sm font-medium text-white bg-[var(--primary-color)] rounded-full hover:bg-[var(--primary-dark)] transition duration-150 ease-in-out">
                            Регистрация
                        </button>
                        <!-- Модальное окно входа -->
                        <template x-teleport="body">
                            <div x-show="$store.modal.isLoginOpen"
                                 x-cloak
                                 @click="$store.modal.closeLogin()"
                                 class="modal-backdrop">
                                <div @click.stop
                                     class="modal-content">
                                    <div class="flex justify-between items-center mb-6">
                                        <h2 class="text-xl font-bold">Вход</h2>
                                        <button @click="$store.modal.closeLogin()"
                                                class="text-gray-400 hover:text-gray-600">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>

                                    <form action="{{ route('login') }}" id="login-form" method="POST" class="space-y-4">
                                        @csrf
                                        <div>
                                            <div class="error-message hidden" id="login-error" style="text-decoration-color: red"></div>
                                            <p class="error-message hidden" id="login-email-error" style="text-decoration-color: red"></p>
                                            <label class="form-label">Email</label>
                                            <input type="email"
                                                   name="email"
                                                   class="form-input"
                                                   placeholder="Введите ваше имя"
                                                   required>
                                        </div>

                                        <div>
                                            <p class="error-message hidden" id="login-password-error" style="text-decoration-color: red"></p>
                                            <label class="form-label">Пароль</label>
                                            <input type="password"
                                                   name="password"
                                                   class="form-input"
                                                   placeholder="Минимум 8 символов"
                                                   required>
                                        </div>

                                        <button type="submit"
                                                class="w-full py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-[var(--primary-color)] hover:bg-[var(--primary-dark)]">
                                            Войти
                                        </button>
                                    </form>

                                    <div class="mt-4 text-center text-sm">
                                        <span class="text-gray-600">Нет аккаунта?</span>
                                        <button @click="$store.modal.switchToRegister()"
                                                class="text-[var(--primary-color)] hover:text-[var(--primary-dark)]">
                                            Зарегистрироваться
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </template>

                        <!-- Модальное окно регистрации -->
                        <template x-teleport="body">
                            <div x-show="$store.modal.isRegisterOpen"
                                 x-cloak
                                 @click="$store.modal.closeRegister()"
                                 class="modal-backdrop">
                                <div @click.stop
                                     class="modal-content">
                                    <div class="flex justify-between items-center mb-6">
                                        <h2 class="text-xl font-bold">Регистрация</h2>
                                        <button @click="$store.modal.closeRegister()"
                                                class="text-gray-400 hover:text-gray-600">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>

                                    <!-- Форма регистрации -->
                                    <form action="{{ route('register') }}" id="register-form" method="POST" class="space-y-4">
                                        @csrf
                                        <!-- Имя -->
                                        <div>
                                            <label class="form-label">Имя</label>
                                            <input type="text"
                                                   name="name"
                                                   class="form-input"
                                                   placeholder="Введите ваше имя"
                                                   required>
                                            <p class="error-message hidden" id="register-name-error"></p>
                                        </div>

                                        <!-- Email -->
                                        <div>
                                            <label class="form-label">Email</label>
                                            <input type="email"
                                                   name="email"
                                                   class="form-input"
                                                   placeholder="example@mail.com"
                                                   required>
                                            <p class="error-message hidden" id="register-email-error"></p>
                                        </div>

                                        <!-- Телефон -->
                                        <div>
                                            <label class="form-label">Телефон</label>
                                            <input type="tel"
                                                   name="phone"
                                                   class="form-input"
                                                   placeholder="+7 (___) ___-__-__"
                                                   required>
                                            <p class="error-message hidden" id="register-phone-error"></p>
                                        </div>

                                        <!-- Пароль -->
                                        <div>
                                            <label class="form-label">Пароль</label>
                                            <input type="password"
                                                   name="password"
                                                   class="form-input"
                                                   placeholder="Минимум 8 символов"
                                                   required>
                                            <p class="error-message hidden" id="register-password-error"></p>
                                        </div>

                                        <!-- Подтверждение пароля -->
                                        <div>
                                            <label class="form-label">Подтверждение пароля</label>
                                            <input type="password"
                                                   name="password_confirmation"
                                                   class="form-input"
                                                   placeholder="Повторите пароль"
                                                   required>
                                            <p class="error-message hidden" id="register-password-confirmation-error"></p>
                                        </div>
                                        <div>
                                            <input class="form-input"
                                                   type="hidden"
                                                   name="roles[]"
                                                   value=""
                                                   >
                                        </div>

                                        <!-- Общая ошибка -->
                                        <div class="error-message hidden" id="register-error"></div>

                                        <button type="submit" class="modal-button modal-button-primary">
                                            Зарегистрироваться
                                        </button>
                                    </form>

                                    <div class="mt-4 text-center text-sm text-gray-600">
                                        Уже есть аккаунт?
                                        <button @click="$store.modal.switchToLogin()"
                                                class="modal-link">
                                            Войти
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>
                @endauth
            </div>
        </div>
    </div>
</header>
    <main>
        @yield('content')
    </main>

<footer class="bg-gray-800 text-white py-8">
    <div class="container mx-auto px-4">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div>
                <h4 class="text-lg font-semibold mb-4">Happy Nest</h4>
                <p class="text-gray-400">Маркетплейс детских товаров</p>
            </div>
            <div>
                <h4 class="text-lg font-semibold mb-4">Помощь</h4>
                <ul class="space-y-2">
                    <li><a href="#" class="text-gray-400 hover:text-white">Как это работает</a></li>
                    <li><a href="#" class="text-gray-400 hover:text-white">Правила безопасности</a></li>
                    <li><a href="#" class="text-gray-400 hover:text-white">Служба поддержки</a></li>
                </ul>
            </div>
            <div>
                <h4 class="text-lg font-semibold mb-4">Контакты</h4>
                <ul class="space-y-2">
                    <li class="text-gray-400">Email: support@happynest.ru</li>
                    <li class="text-gray-400">Телефон: 8 800 123-45-67</li>
                </ul>
            </div>
        </div>
        <div class="border-t border-gray-700 mt-8 pt-8 text-center text-gray-400">
            <p>&copy; {{ date('Y') }} Happy Nest. Все права защищены.</p>
        </div>
    </div>
</footer>

<!-- Font Awesome -->
<script>
    function clearErrors(formPrefix) {
        document.querySelectorAll(`[id^="${formPrefix}-"][id$="-error"]`).forEach(el => {
            el.textContent = '';
            el.classList.add('hidden');
        });
    }

    function showErrors(errors, formPrefix) {
        clearErrors(formPrefix);
        Object.keys(errors).forEach(field => {
            const errorElement = document.getElementById(`${formPrefix}-${field}-error`);
            if (errorElement) {
                errorElement.textContent = errors[field][0];
                errorElement.classList.remove('hidden');
            }
        });
    }

    // Инициализация Alpine.js store
    document.addEventListener('alpine:init', () => {
        Alpine.store('modal', {
            isLoginOpen: false,
            isRegisterOpen: false,
            isReportOpen: false,

            openLogin() {
                this.isLoginOpen = true;
                this.isRegisterOpen = false;
                document.body.classList.add('modal-open');
            },

            closeLogin() {
                this.isLoginOpen = false;
                document.body.classList.remove('modal-open');
            },

            openRegister() {
                this.isRegisterOpen = true;
                this.isLoginOpen = false;
                document.body.classList.add('modal-open');
            },

            closeRegister() {
                this.isRegisterOpen = false;
                document.body.classList.remove('modal-open');
            },
            openReport() {
                this.isReportOpen = true;
                document.body.classList.add('modal-open');
            },

            closeReport() {
                this.isReportOpen = false;
                document.body.classList.remove('modal-open');
            },

            switchToRegister() {
                this.isLoginOpen = false;
                this.isRegisterOpen = true;
            },

            switchToLogin() {
                this.isRegisterOpen = false;
                this.isLoginOpen = true;
            },
        });
    });

    // Один общий обработчик DOMContentLoaded
    document.addEventListener('DOMContentLoaded', function() {
        // Обработчик формы входа
        const loginForm = document.getElementById('login-form');
        if (loginForm) {
            loginForm.addEventListener('submit', async function(e) {
                e.preventDefault();
                const formData = new FormData(this);

                try {
                    const response = await fetch(this.action, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        credentials: 'include',
                        body: formData
                    });

                    const data = await response.json();

                    if (!response.ok) {
                        if (response.status === 422) {
                            showErrors(data.errors, 'login');
                        } else {
                            const errorElement = document.getElementById('login-error');
                            if (errorElement) {
                                errorElement.textContent = data.message || 'Ошибка входа';
                                errorElement.classList.remove('hidden');
                            }
                        }
                        return;
                    }

                    if (data.success) {
                        window.location.href = data.redirect;
                    }

                } catch (error) {
                    console.error('Error:', error);
                }
            });
        }

        // Обработчик формы регистрации
        const registerForm = document.getElementById('register-form');
        if (registerForm) {
            registerForm.addEventListener('submit', async function(e) {
                e.preventDefault();
                const formData = new FormData(this);

                try {
                    const response = await fetch(this.action, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        credentials: 'include',
                        body: formData
                    });

                    const data = await response.json();

                    if (!response.ok) {
                        if (response.status === 422) {
                            showErrors(data.errors, 'register');
                        } else {
                            const errorElement = document.getElementById('register-error');
                            if (errorElement) {
                                errorElement.textContent = data.message || 'Ошибка регистрации';
                                errorElement.classList.remove('hidden');
                            }
                        }
                        return;
                    }

                    if (data.success) {
                        window.location.href = data.redirect;
                    }

                } catch (error) {
                    console.error('Error:', error);
                }
            });
        }

        // Обработчик защищенных ссылок
        document.querySelectorAll('[data-auth-required]').forEach(element => {
            element.addEventListener('click', function(e) {
                if (!document.body.classList.contains('user-authenticated')) {
                    e.preventDefault();
                    Alpine.store('modal').openLogin();
                }
            });
        });

        // Открытие модального окна если есть флаг в сессии
        @if(session('showAuthModal'))
        setTimeout(() => {
            Alpine.store('modal').openLogin();
        }, 100);
        @endif
    });

    // Обработчик клавиши Escape
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            Alpine.store('modal').closeLogin();
            Alpine.store('modal').closeRegister();
        }
    });
</script>
</body>
</html>
