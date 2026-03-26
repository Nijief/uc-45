document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const searchResults = document.getElementById('searchResults');
    const searchForm = document.getElementById('searchForm');
    
    if (!searchInput || !searchResults) return;
    
    let searchTimeout;
    
    async function performSearch(query) {
        if (!query || query.length < 2) {
            searchResults.style.display = 'none';
            return;
        }
        
        try {
            const response = await fetch('/Test/admin/search.php?q=' + encodeURIComponent(query));
            
            if (!response.ok) {
                throw new Error('Ошибка сети: ' + response.status);
            }
            
            const results = await response.json();
            
            if (results && results.length > 0) {
                searchResults.innerHTML = results.map(item => `
                    <div class="search-result-item" onclick="window.location.href='${item.url}'">
                        <div class="search-result-title">${item.title}</div>
                        <div class="search-result-description">${item.description}</div>
                        <div class="search-result-category">${item.type === 'program' ? '📚 Программа' : '📰 Новость'}</div>
                    </div>
                `).join('');
                searchResults.style.display = 'block';
            } else {
                searchResults.innerHTML = '<div class="search-no-results">Ничего не найдено</div>';
                searchResults.style.display = 'block';
            }
        } catch (error) {
            console.error('Ошибка поиска:', error);
            searchResults.innerHTML = '<div class="search-no-results">Ошибка поиска. Попробуйте позже.</div>';
            searchResults.style.display = 'block';
        }
    }
    
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
    
    if (searchForm) {
        searchForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const query = searchInput.value.trim();
            if (query.length >= 2) {
                performSearch(query);
            }
        });
    }
    
    document.addEventListener('click', function(e) {
        const searchContainer = document.querySelector('.search-container');
        if (searchContainer && !searchContainer.contains(e.target)) {
            searchResults.style.display = 'none';
        }
    });
    
    const currentPath = window.location.pathname;
    document.querySelectorAll('.nav-menu a').forEach(link => {
        const href = link.getAttribute('href');
        if (href === currentPath || 
            (currentPath === '/' || currentPath === '/index.php') && href === '/index.php') {
            link.classList.add('active');
        }
    });
});