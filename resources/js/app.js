import './bootstrap';
import Alpine from 'alpinejs';

window.Alpine = Alpine;

const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
if (csrfToken && window.axios) {
    window.axios.defaults.headers.common['X-CSRF-TOKEN'] = csrfToken;
}

window.getCsrfToken = () => csrfToken ?? '';

window.castReviewVote = async (state, voteUrl, voteType) => {
    if (state.voting || state.voted) {
        return;
    }

    state.voting = true;

    try {
        const response = await fetch(voteUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': window.getCsrfToken(),
                'Accept': 'application/json',
            },
            body: JSON.stringify({ vote_type: voteType }),
        });

        const data = await response.json();

        if (!response.ok) {
            throw new Error(data.message || 'Не удалось отправить голос');
        }

        state.voted = true;
        window.showToast?.(data.message, 'success');
        window.dispatchEvent(new CustomEvent('review-voted', { detail: { reviewId: data.review_id, voteType: data.vote_type } }));
    } catch (error) {
        window.showToast?.(error.message, 'error');
    } finally {
        state.voting = false;
    }
};

Alpine.data('reviewsFragmentLoader', (fragmentUrl) => ({
    loading: false,

    init() {
        window.addEventListener('review-voted', () => this.load());
    },

    async load(page = null) {
        this.loading = true;

        try {
            const url = new URL(fragmentUrl, window.location.origin);
            const pageParam = page ?? new URLSearchParams(window.location.search).get('page');

            if (pageParam) {
                url.searchParams.set('page', pageParam);
            }

            const response = await fetch(url, {
                headers: {
                    Accept: 'text/html',
                    'X-Requested-With': 'XMLHttpRequest',
                },
            });

            if (!response.ok) {
                throw new Error('Не удалось загрузить отзывы');
            }

            const html = await response.text();
            const container = document.getElementById('reviews-list-container');

            if (container) {
                container.innerHTML = html;
            }
        } catch (error) {
            window.showToast?.(error.message, 'error');
        } finally {
            this.loading = false;
        }
    },

    onPaginationClick(event) {
        const link = event.target.closest('a[href]');

        if (!link) {
            return;
        }

        event.preventDefault();

        const page = new URL(link.href, window.location.origin).searchParams.get('page');
        this.load(page);
        history.replaceState({}, '', link.href);
    },
}));

// 🔒 БЛОК 1: ГЛОБАЛЬНЫЙ КОМПОНЕНТ ТЕМЫ
Alpine.data('themeSwitcher', () => ({
    isDark: false,

    init() {
        this.isDark = localStorage.getItem('theme') !== 'light';
        this.updateTheme();
    },

    toggleTheme() {
        this.isDark = !this.isDark;
        localStorage.setItem('theme', this.isDark ? 'dark' : 'light');
        this.updateTheme();
    },

    updateTheme() {
        if (this.isDark) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    }
}));

// 🔒 БЛОК 2: КОМПОНЕНТ КОНСТРУКТОРА ОПРОСОВ
Alpine.data('pollBuilder', (pollId, initialQuestions, redirectUrl) => ({
    // 🔑 КРИТИЧНОЕ СОСТОЯНИЕ
    pollId,
    questions: Array.isArray(initialQuestions) ? initialQuestions : [],
    showTypeSelector: false,
    showDeleteModal: false,
    questionToDeleteIndex: null,
    questionToDeleteText: '',
    saving: false,
    isDark: localStorage.getItem('theme') !== 'light',
    redirectUrl,
    insertIndex: null,
    currentView: 'constructor',
    draggedIndex: null,

    // 🔑 НОВЫЕ ПЕРЕМЕННЫЕ ДЛЯ ТЕСТОВОГО РЕЖИМА
    isTest: false,
    testSettings: {
        points_per_question: 10,
        time_limit: null,
        time_per_question: null,
        show_timer: true,
        grading_scale: 'five_point',
        passing_score: 60,
        grade_2_max: 59,
        grade_3_min: 60, grade_3_max: 74,
        grade_4_min: 75, grade_4_max: 89,
        grade_5_min: 90,
        shuffle_questions: false,
        show_correct_answers: false,
        one_attempt: false
    },

    // 🔑 НАСТРОЙКИ ВОПРОСА
    questionSettingsOpen: false,
    currentQuestionIndex: null,

    // 🔑 СВОЙСТВА ДЛЯ ВКЛАДКИ "ПРОСМОТР"
    previewStep: 0,
    previewAnswers: {},
    previewStarted: false,
    previewFinished: false,

    // 🔑 ШАБЛОНЫ ВОПРОСОВ
    questionDefaults: {
        welcome: { description: '', text: '' },
        message: { description: '', text: '' },
        group: { description: '', text: '', subQuestions: [] },
        end: { description: '', text: '' },
        number: { description: '', text: '', placeholder: '0', min: 0, max: 100 },
        text: { description: '', text: '', placeholder: 'Напишите ответ здесь...' },
        file_upload: { description: '', text: '' },
        image_upload: { description: '', text: '' },
        radio: { description: '', text: '', options: ['', ''], correct_answers: [], points: 10, time_limit: null },
        checkbox: { description: '', text: '', options: ['', ''], correct_answers: [], points: 10, time_limit: null },
        media: { description: '', text: '', options: [], multiple: false },
        yesno: { description: '', text: '', correct_answers: [], points: 10 },
        dropdown: { description: '', text: '', options: ['', ''], correct_answers: [], points: 10 },
        smiley_rating: { description: '', text: '' },
        rating: { description: '', text: '', max: 5 },
        ranking: { description: '', text: '', options: ['Вариант 1', 'Вариант 2', 'Вариант 3'] },
        scale: { description: '', text: '', min: 1, max: 5, left_label: 'Не согласен', right_label: 'Согласен' },
        slider: { description: '', text: '', min: 0, max: 100 },
        distribution_scale: { description: '', text: '', min: 0, max: 100 },
        matrix: { description: '', text: '', rows: ['Ряд 1', 'Ряд 2'], cols: ['1', '2', '3', '4', '5'] },
        semantic_differential: { description: '', text: '', left: 'Негативно', right: 'Позитивно' }
    },

    // 🔑 ИНИЦИАЛИЗАЦИЯ
    init() {
        this.updateTheme();
        
        // 🔹 Инициализируем вопросы с данными из БД
        this.questions = this.questions.map(q => {
            const defaults = this.questionDefaults[q.type] || {};
            return {
                id: q.id || Date.now() + '_' + Math.random().toString(36).substr(2, 5),
                type: q.type || 'text',
                text: q.text || '',
                description: q.description || '',
                options: Array.isArray(q.options) ? q.options : [],
                is_required: q.is_required || false,
                // Параметры для тестового режима
                points: q.points ?? (this.isTest ? 10 : null),
                correct_answers: Array.isArray(q.correct_answers) ? q.correct_answers : [],
                time_limit: q.time_limit ?? null,
                // Остальные параметры
                placeholder: q.placeholder ?? defaults.placeholder ?? '',
                min: q.min ?? defaults.min ?? null,
                max: q.max ?? defaults.max ?? null,
                left: q.left ?? defaults.left ?? '',
                right: q.right ?? defaults.right ?? '',
                left_label: q.left_label ?? defaults.left_label ?? '',
                right_label: q.right_label ?? defaults.right_label ?? '',
                multiple: q.multiple ?? defaults.multiple ?? false,
                rows: q.rows ?? defaults.rows ?? [],
                cols: q.cols ?? defaults.cols ?? [],
                subQuestions: q.subQuestions || []
            };
        });
    },

    // 🔑 ТЕМЫ
    toggleTheme() {
        this.isDark = !this.isDark;
        localStorage.setItem('theme', this.isDark ? 'dark' : 'light');
        this.updateTheme();
    },

    updateTheme() {
        if (this.isDark) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    },

    // 🔑 УПРАВЛЕНИЕ СКРОЛЛОМ
    lockScroll(isLocked) {
        document.body.style.overflow = isLocked ? 'hidden' : '';
        document.body.style.paddingRight = isLocked ? '15px' : '';
    },

    // 🔑 ДОБАВЛЕНИЕ ВОПРОСА
    addQuestion(type, index = null) {
        const base = { 
            type, 
            text: '', 
            description: '', 
            options: [], 
            is_required: false,
            id: Date.now() + '_' + Math.random().toString(36).substr(2, 5),
            points: this.isTest ? 10 : null,
            correct_answers: [],
            time_limit: null
        };
        const defaults = this.questionDefaults[type] || {};
        const newQ = { ...base, ...defaults };

        if (index === null || index < 0) {
            this.questions.push(newQ);
        } else {
            this.questions.splice(index, 0, newQ);
        }
        this.insertIndex = null;
    },

    // 🔑 ДУБЛИРОВАНИЕ ВОПРОСА
    duplicateQuestion(index) {
        const copy = JSON.parse(JSON.stringify(this.questions[index]));
        copy.id = Date.now() + '_' + Math.random().toString(36).substr(2, 5);
        this.questions.splice(index + 1, 0, copy);
    },

    // 🔑 DRAG & DROP
    dragStart(event, index) {
        this.draggedIndex = index;
        event.dataTransfer.effectAllowed = 'move';
        setTimeout(() => event.target.classList.add('opacity-50', 'scale-95'), 0);
    },

    dragEnd(event) {
        this.draggedIndex = null;
        event.target.classList.remove('opacity-50', 'scale-95');
    },

    dragDrop(event, dropIndex) {
        if (this.draggedIndex === null || this.draggedIndex === dropIndex) return;
        const item = this.questions.splice(this.draggedIndex, 1)[0];
        this.questions.splice(dropIndex, 0, item);
        this.draggedIndex = null;
    },

    // 🔑 ВАРИАНТЫ ОТВЕТОВ
    addOption(qIndex) { 
        this.questions[qIndex].options.push(''); 
    },

    addOptionsList(qIndex) {
        const list = prompt('Введите варианты, каждый с новой строки:');
        if (list) {
            this.questions[qIndex].options = [
                ...this.questions[qIndex].options, 
                ...list.split('\n').filter(i => i.trim())
            ];
        }
    },

    removeOption(qIndex, oIndex) { 
        this.questions[qIndex].options.splice(oIndex, 1); 
    },

    // 🔑 УДАЛЕНИЕ ВОПРОСА
    removeQuestion(index, text) {
        this.questionToDeleteIndex = index;
        this.questionToDeleteText = text || 'Вопрос без названия';
        this.showDeleteModal = true;
        this.lockScroll(true);
    },

    confirmRemoveQuestion() {
        if (this.questionToDeleteIndex !== null) {
            this.questions.splice(this.questionToDeleteIndex, 1);
        }
        this.showDeleteModal = false;
        this.lockScroll(false);
    },

    // 🔑 ПЕРЕМЕЩЕНИЕ ВОПРОСА
    moveQuestion(index, direction) {
        if (direction === 'up' && index > 0) {
            [this.questions[index], this.questions[index-1]] = [this.questions[index-1], this.questions[index]];
        } else if (direction === 'down' && index < this.questions.length - 1) {
            [this.questions[index], this.questions[index+1]] = [this.questions[index+1], this.questions[index]];
        }
    },

    // 🔑 НОВЫЕ МЕТОДЫ ДЛЯ НАСТРОЕК ВОПРОСА
    openQuestionSettings(index) {
        this.currentQuestionIndex = index;
        this.questionSettingsOpen = true;
    },

    saveQuestionSettings() {
        const question = this.questions[this.currentQuestionIndex];
        
        // Валидация для тестового режима
        if (this.isTest && ['radio', 'checkbox', 'dropdown'].includes(question.type)) {
            if (!question.correct_answers || question.correct_answers.length === 0) {
                showToast('⚠️ Отметьте хотя бы один правильный ответ!', 'error');
                return;
            }
            if (question.type === 'radio' && question.correct_answers.length > 1) {
                showToast('⚠️ Для "Выбор одного" можно отметить только один правильный ответ', 'error');
                return;
            }
        }
        
        if (this.isTest && (!question.points || question.points < 1)) {
            showToast('⚠️ Укажите количество баллов за вопрос (минимум 1)', 'error');
            return;
        }
        
        if (question.time_limit !== null && question.time_limit !== undefined) {
            if (question.time_limit < 10 || question.time_limit > 600) {
                showToast('⚠️ Время на вопрос должно быть от 10 до 600 секунд', 'error');
                return;
            }
        }
        
        this.questionSettingsOpen = false;
        showToast('✅ Настройки вопроса сохранены', 'success');
    },

    // 🔑 СОХРАНЕНИЕ НАСТРОЕК ТЕСТА
    async saveTestSettings() {
        this.saving = true;
        try {
            const payload = {
                is_test: this.isTest,
                test_settings: this.testSettings,
                questions: this.questions.map(q => ({
                    id: q.id,
                    type: q.type,
                    text: q.text,
                    description: q.description,
                    options: q.options,
                    is_required: q.is_required,
                    points: q.points,
                    correct_answers: q.correct_answers,
                    time_limit: q.time_limit,
                    placeholder: q.placeholder,
                    min: q.min,
                    max: q.max,
                    left: q.left,
                    right: q.right,
                    left_label: q.left_label,
                    right_label: q.right_label,
                    multiple: q.multiple,
                    rows: q.rows,
                    cols: q.cols,
                    sort_order: this.questions.indexOf(q)
                }))
            };

            const res = await fetch(`/polls/${this.pollId}/questions`, {
                method: 'POST',
                headers: { 
                    'Content-Type': 'application/json', 
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content 
                },
                body: JSON.stringify(payload)
            });

            if (res.ok) {
                showToast('Настройки теста сохранены!', 'success');
                setTimeout(() => window.location.reload(), 1000);
            } else {
                const error = await res.json().catch(() => ({}));
                throw new Error(error.message || 'Ошибка сохранения');
            }
        } catch (e) { 
            console.error('Save error:', e);
            showToast('Не удалось сохранить: ' + e.message, 'error');
        } finally { 
            this.saving = false; 
        }
    },

    // 🔑 ЛЕЙБЛЫ ТИПОВ ВОПРОСОВ
    getQuestionTypeLabel(type) {
        const map = {
            welcome: 'Экран приветствия', 
            message: 'Сообщение', 
            group: 'Группа вопросов', 
            end: 'Экран благодарности', 
            number: 'Числовой ответ',
            text: 'Поле ввода', 
            file_upload: 'Загрузка файлов', 
            image_upload: 'Загрузка изображения',
            radio: 'Выбор одного', 
            checkbox: 'Выбор нескольких', 
            media: 'Выбор медиа', 
            yesno: 'Да или Нет', 
            dropdown: 'Выпадающий список',
            smiley_rating: 'Смайл-рейтинг', 
            rating: 'Оценка', 
            ranking: 'Ранжирование', 
            scale: 'Шкала', 
            slider: 'Ползунок',
            distribution_scale: 'Распределительная шкала', 
            matrix: 'Матрица', 
            semantic_differential: 'Семантический дифференциал'
        };
        return map[type] || type;
    },

    // 🔑 СОХРАНЕНИЕ ОПРОСА
    async savePoll() {
        if (this.questions.length === 0) return;
        this.saving = true;
        try {
            const payload = {
                questions: this.questions.map(q => ({
                    id: q.id,
                    type: q.type,
                    text: q.text,
                    description: q.description,
                    options: q.options,
                    is_required: q.is_required,
                    points: q.points,
                    correct_answers: q.correct_answers,
                    time_limit: q.time_limit,
                    placeholder: q.placeholder,
                    min: q.min,
                    max: q.max,
                    left: q.left,
                    right: q.right,
                    left_label: q.left_label,
                    right_label: q.right_label,
                    multiple: q.multiple,
                    rows: q.rows,
                    cols: q.cols,
                    sort_order: this.questions.indexOf(q)
                })),
                is_test: this.isTest,
                test_settings: this.testSettings
            };

            const res = await fetch(`/polls/${this.pollId}/questions`, {
                method: 'POST',
                headers: { 
                    'Content-Type': 'application/json', 
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content 
                },
                body: JSON.stringify(payload)
            });

            if (res.ok) {
                window.location.href = this.redirectUrl;
            } else {
                const error = await res.json().catch(() => ({}));
                throw new Error(error.message || 'Ошибка сохранения');
            }
        } catch (e) { 
            console.error('Save error:', e);
            alert('Не удалось сохранить изменения: ' + e.message); 
        } finally { 
            this.saving = false; 
        }
    },

    // 🔑 ЗАГРУЗКА ФАЙЛОВ/ИЗОБРАЖЕНИЙ
    handleFileUpload(qIndex, event, type) {
        const files = Array.from(event.target.files);
        
        files.forEach(file => {
            const fileData = { 
                name: file.name, 
                size: (file.size / 1024 / 1024).toFixed(2) + ' MB', 
                type: file.type, 
                preview: null,
                isTemp: true
            };
            
            if (type === 'image_upload' && file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = (e) => {
                    fileData.preview = e.target.result;
                    this.questions[qIndex].options.push({...fileData});
                };
                reader.readAsDataURL(file);
            } else {
                this.questions[qIndex].options.push({...fileData});
            }
        });
        
        event.target.value = '';
    },

    removeFile(qIndex, fIndex) {
        this.questions[qIndex].options.splice(fIndex, 1);
    },

    // 🔑 МЕТОДЫ ДЛЯ ВКЛАДКИ "ПРОСМОТР"
    startPreview() {
        this.previewStep = 0;
        this.previewAnswers = {};
        this.previewStarted = true;
        this.previewFinished = false;
    },
    nextPreviewStep() {
        if (this.previewStep < this.questions.length - 1) {
            this.previewStep++;
        } else {
            this.previewFinished = true;
        }
    },
    prevPreviewStep() {
        if (this.previewStep > 0) {
            this.previewStep--;
        }
    },
    setPreviewAnswer(value) {
        this.previewAnswers[this.previewStep] = value;
    },

    // 🔑 НОВЫЕ МЕТОДЫ ДЛЯ ПОДСЧЁТА БАЛЛОВ В ПРЕВЬЮ
    calculatePreviewScore() {
        let score = 0;
        this.questions.forEach((q, idx) => {
            if (q.correct_answers && q.correct_answers.includes(this.previewAnswers[idx])) {
                score += q.points || this.testSettings.points_per_question;
            }
        });
        return score;
    },

    getPreviewGrade() {
        const score = this.calculatePreviewScore();
        const maxScore = this.questions.length * (this.testSettings.points_per_question || 10);
        const percent = (score / maxScore) * 100;
        
        if (this.testSettings.grading_scale === 'five_point') {
            if (percent >= (this.testSettings.grade_5_min || 90)) return '5 (Отлично)';
            if (percent >= (this.testSettings.grade_4_min || 75)) return '4 (Хорошо)';
            if (percent >= (this.testSettings.grade_3_min || 60)) return '3 (Удовлетворительно)';
            return '2 (Неудовлетворительно)';
        } else if (this.testSettings.grading_scale === 'verbal') {
            return percent >= (this.testSettings.passing_score || 60) ? 'Зачёт' : 'Не зачёт';
        }
        return Math.round(percent) + '%';
    }
}));

// 🔹 Вспомогательная функция для уведомлений
function showToast(message, type = 'success') {
    const toast = document.createElement('div');
    toast.className = `fixed bottom-4 right-4 px-4 py-2 rounded-lg shadow-lg z-50 text-white transition-all duration-300 transform translate-y-20 opacity-0 ${
        type === 'success' ? 'bg-green-600' : 'bg-red-600'
    }`;
    toast.textContent = message;
    document.body.appendChild(toast);
    
    setTimeout(() => {
        toast.classList.remove('translate-y-20', 'opacity-0');
    }, 10);
    
    setTimeout(() => {
        toast.classList.add('translate-y-20', 'opacity-0');
        setTimeout(() => toast.remove(), 300);
    }, 2000);
}

window.showToast = showToast;

// 🔒 ЗАПУСК ALPINE.JS
Alpine.start();