// assets/js/script.js

document.addEventListener('DOMContentLoaded', function() {
    // Поиск по сайту
    const searchInput = document.getElementById('searchInput');
    const searchResults = document.getElementById('searchResults');
    const searchForm = document.getElementById('searchForm');
    
    if (searchInput) {
        let searchTimeout;
        
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            const query = this.value.trim();
            
            if (query.length < 2) {
                searchResults.style.display = 'none';
                return;
            }
            
            searchTimeout = setTimeout(() => {
                performSearch(query);
            }, 300);
        });
        
        searchForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const query = searchInput.value.trim();
            if (query.length >= 2) {
                performSearch(query);
            }
        });
        
        // Закрытие результатов при клике вне
        document.addEventListener('click', function(e) {
            if (!searchContainer.contains(e.target)) {
                searchResults.style.display = 'none';
            }
        });
    }
    
    async function performSearch(query) {
        try {
            // Здесь будет AJAX-запрос к серверу для поиска
            // Пока демонстрационные данные
            const results = [
                { title: 'КИПиА и промышленная автоматика', description: 'Настройка, программирование контроллеров...', type: 'program', url: '/pages/program_detail.php?id=1' },
                { title: 'Открыт набор на весенние курсы', description: 'Старт занятий 10 апреля...', type: 'news', url: '/pages/news_detail.php?id=1' }
            ].filter(item => 
                item.title.toLowerCase().includes(query.toLowerCase()) ||
                item.description.toLowerCase().includes(query.toLowerCase())
            );
            
            if (results.length > 0) {
                searchResults.innerHTML = results.map(item => `
                    <div class="search-result-item" onclick="window.location.href='${item.url}'">
                        <div class="search-result-title">${escapeHtml(item.title)}</div>
                        <div class="search-result-description">${escapeHtml(item.description.substring(0, 100))}</div>
                        <div class="search-result-category">${item.type === 'program' ? 'Программа' : 'Новость'}</div>
                    </div>
                `).join('');
                searchResults.style.display = 'block';
            } else {
                searchResults.innerHTML = '<div class="search-no-results">Ничего не найдено</div>';
                searchResults.style.display = 'block';
            }
        } catch (error) {
            console.error('Ошибка поиска:', error);
        }
    }
    
    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }
    
    // Активная ссылка в меню
    const currentPath = window.location.pathname;
    document.querySelectorAll('.nav-menu a').forEach(link => {
        if (link.getAttribute('href') === currentPath || 
            (currentPath === '/' || currentPath === '/index.php') && link.getAttribute('href') === '/index.php') {
            link.classList.add('active');
        }
    });
});