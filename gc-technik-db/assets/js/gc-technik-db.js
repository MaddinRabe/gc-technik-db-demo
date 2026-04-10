/**
 * GrandCali Technik-DB — Frontend JavaScript
 */
(function () {
    'use strict';

    /* ======================================================================
       Search
       ====================================================================== */

    function initSearch() {
        var wrapper = document.querySelector('.gc-search-wrapper');
        if (!wrapper) return;

        var input       = wrapper.querySelector('.gc-search-input');
        var filters     = wrapper.querySelectorAll('.gc-filter');
        var resetBtn    = wrapper.querySelector('.gc-filter-reset');
        var results     = wrapper.querySelector('.gc-search-results');
        var loading     = wrapper.querySelector('.gc-search-loading');
        var noResults   = wrapper.querySelector('.gc-search-no-results');
        var status      = wrapper.querySelector('.gc-search-status');
        var countEl     = wrapper.querySelector('.gc-search-count');
        var pagination  = wrapper.querySelector('.gc-search-pagination');
        var loadMoreBtn = wrapper.querySelector('.gc-load-more');
        var perPage     = parseInt(wrapper.dataset.perPage, 10) || 12;
        var currentPage = 1;
        var debounceTimer;

        function getFilters() {
            var f = {};
            filters.forEach(function (select) {
                if (select.value) {
                    f[select.dataset.taxonomy] = select.value;
                }
            });
            return f;
        }

        function doSearch(append) {
            if (!append) {
                currentPage = 1;
            }

            var query = input.value.trim();
            var filterValues = getFilters();
            var hasFilters = Object.keys(filterValues).length > 0;

            if (!query && !hasFilters) {
                results.innerHTML = '';
                loading.style.display = 'none';
                noResults.style.display = 'none';
                status.style.display = 'none';
                pagination.style.display = 'none';
                resetBtn.style.display = 'none';
                return;
            }

            resetBtn.style.display = hasFilters ? 'inline-block' : 'none';

            if (!append) {
                loading.style.display = 'block';
                results.innerHTML = '';
                noResults.style.display = 'none';
                status.style.display = 'none';
                pagination.style.display = 'none';
            }

            var data = new FormData();
            data.append('action', 'gc_search');
            data.append('nonce', gcTechnikDB.nonce);
            data.append('query', query);
            data.append('page', currentPage);
            data.append('per_page', perPage);

            Object.keys(filterValues).forEach(function (key) {
                data.append(key, filterValues[key]);
            });

            fetch(gcTechnikDB.ajaxUrl, {
                method: 'POST',
                body: data,
            })
                .then(function (res) { return res.json(); })
                .then(function (res) {
                    loading.style.display = 'none';

                    if (!res.success || !res.data.results.length) {
                        if (!append) {
                            noResults.style.display = 'block';
                            status.style.display = 'none';
                        }
                        pagination.style.display = 'none';
                        return;
                    }

                    var d = res.data;
                    countEl.textContent = d.total;
                    status.style.display = 'block';
                    noResults.style.display = 'none';

                    d.results.forEach(function (item) {
                        results.insertAdjacentHTML('beforeend', renderCard(item));
                    });

                    pagination.style.display = (d.page < d.pages) ? 'block' : 'none';
                })
                .catch(function () {
                    loading.style.display = 'none';
                });
        }

        function renderCard(item) {
            var badges = '';
            if (item.categories && item.categories.length) {
                badges += '<span class="gc-badge gc-badge-category">' + escHtml(item.categories[0]) + '</span>';
            }
            if (item.vw_code) {
                badges += '<span class="gc-badge gc-badge-code">' + escHtml(item.vw_code) + '</span>';
            }

            return '<div class="gc-search-result-card">' +
                '<a href="' + escHtml(item.url) + '">' +
                '<h3 class="gc-search-result-title">' + escHtml(item.title) + '</h3>' +
                '<p class="gc-search-result-excerpt">' + item.excerpt + '</p>' +
                '<div class="gc-search-result-meta">' + badges + '</div>' +
                '</a></div>';
        }

        input.addEventListener('input', function () {
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(function () {
                doSearch(false);
            }, 300);
        });

        filters.forEach(function (select) {
            select.addEventListener('change', function () {
                doSearch(false);
            });
        });

        if (resetBtn) {
            resetBtn.addEventListener('click', function () {
                filters.forEach(function (s) { s.value = ''; });
                input.value = '';
                results.innerHTML = '';
                status.style.display = 'none';
                noResults.style.display = 'none';
                pagination.style.display = 'none';
                resetBtn.style.display = 'none';
            });
        }

        if (loadMoreBtn) {
            loadMoreBtn.addEventListener('click', function () {
                currentPage++;
                doSearch(true);
            });
        }
    }

    /* ======================================================================
       Table of Contents
       ====================================================================== */

    function initTOC() {
        var toc = document.querySelector('.gc-toc');
        if (!toc) return;

        // Toggle
        var toggle = toc.querySelector('.gc-toc-toggle');
        var list = toc.querySelector('.gc-toc-list');

        if (toggle && list) {
            toggle.addEventListener('click', function () {
                var expanded = toggle.getAttribute('aria-expanded') === 'true';
                toggle.setAttribute('aria-expanded', !expanded);
                list.setAttribute('aria-hidden', expanded);
            });
        }

        // Smooth scroll
        var links = toc.querySelectorAll('.gc-toc-link');
        links.forEach(function (link) {
            link.addEventListener('click', function (e) {
                e.preventDefault();
                var id = link.getAttribute('href').substring(1);
                var target = document.getElementById(id);
                if (target) {
                    target.scrollIntoView({ behavior: 'smooth', block: 'start' });
                    history.replaceState(null, '', '#' + id);
                }
            });
        });

        // Active state via Intersection Observer
        var headings = [];
        links.forEach(function (link) {
            var id = link.getAttribute('href').substring(1);
            var el = document.getElementById(id);
            if (el) headings.push({ el: el, link: link });
        });

        if (!headings.length || !('IntersectionObserver' in window)) return;

        var observer = new IntersectionObserver(function (entries) {
            entries.forEach(function (entry) {
                if (entry.isIntersecting) {
                    links.forEach(function (l) { l.classList.remove('active'); });
                    var match = headings.find(function (h) { return h.el === entry.target; });
                    if (match) match.link.classList.add('active');
                }
            });
        }, {
            rootMargin: '-80px 0px -60% 0px',
            threshold: 0,
        });

        headings.forEach(function (h) {
            observer.observe(h.el);
        });

        // Move TOC into sidebar on single articles if sidebar exists
        var sidebar = document.getElementById('gc-toc-sidebar');
        if (sidebar && toc.parentNode !== sidebar) {
            sidebar.appendChild(toc);
        }
    }

    /* ======================================================================
       Helpers
       ====================================================================== */

    function escHtml(str) {
        var div = document.createElement('div');
        div.textContent = str || '';
        return div.innerHTML;
    }

    /* ======================================================================
       Init
       ====================================================================== */

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }

    function init() {
        initSearch();
        initTOC();
    }

})();
