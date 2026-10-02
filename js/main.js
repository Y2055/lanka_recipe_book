/**
 * Lanka Recipe Book - Modern Interactive JavaScript
 * Full client-side interactivity: theme switcher, recipe modal, portion scaler,
 * kitchen timer, favorites, live filtering, and form validations.
 */

document.addEventListener('DOMContentLoaded', () => {

  // =========================================================================
  // 1. Theme Management (Light / Dark mode)
  // =========================================================================
  const themeToggleBtns = document.querySelectorAll('.theme-toggle-btn');
  
  function updateThemeUI(theme) {
    document.documentElement.setAttribute('data-bs-theme', theme);
    localStorage.setItem('lrb_theme', theme);

    themeToggleBtns.forEach(btn => {
      const moon = btn.querySelector('.theme-icon-moon');
      const sun = btn.querySelector('.theme-icon-sun');
      if (theme === 'dark') {
        moon?.classList.add('d-none');
        sun?.classList.remove('d-none');
      } else {
        sun?.classList.add('d-none');
        moon?.classList.remove('d-none');
      }
    });
  }

  const currentTheme = document.documentElement.getAttribute('data-bs-theme') || 'light';
  updateThemeUI(currentTheme);

  themeToggleBtns.forEach(btn => {
    btn.addEventListener('click', () => {
      const active = document.documentElement.getAttribute('data-bs-theme');
      const next = active === 'dark' ? 'light' : 'dark';
      updateThemeUI(next);
      showToast(`Switched to ${next} mode`);
    });
  });

  // =========================================================================
  // 2. Toast Notification Helper
  // =========================================================================
  const toastEl = document.getElementById('appToast');
  let appToast = null;
  if (toastEl && window.bootstrap) {
    appToast = new bootstrap.Toast(toastEl, { delay: 2800 });
  }

  function showToast(message, isSuccess = true) {
    if (!toastEl || !appToast) return;
    const msgEl = document.getElementById('toastMessage');
    const iconEl = toastEl.querySelector('.toast-icon');
    if (msgEl) msgEl.textContent = message;
    if (iconEl) {
      iconEl.className = isSuccess 
        ? 'bi bi-check-circle-fill text-success fs-5 toast-icon' 
        : 'bi bi-info-circle-fill text-warning fs-5 toast-icon';
    }
    appToast.show();
  }
  window.showToast = showToast;

  // =========================================================================
  // 3. Favorites / Bookmarks (Stored in localStorage)
  // =========================================================================
  function getFavorites() {
    try {
      return JSON.parse(localStorage.getItem('lrb_favorites') || '[]');
    } catch {
      return [];
    }
  }

  function saveFavorites(favs) {
    localStorage.setItem('lrb_favorites', JSON.stringify(favs));
    updateFavoritesUI();
  }

  function toggleFavorite(id) {
    id = String(id);
    let favs = getFavorites();
    const index = favs.indexOf(id);
    let added = false;
    if (index > -1) {
      favs.splice(index, 1);
      showToast('Removed recipe from favorites', false);
    } else {
      favs.push(id);
      added = true;
      showToast('Saved to your favorite recipes! ❤️');
    }
    saveFavorites(favs);
    return added;
  }

  function updateFavoritesUI() {
    const favs = getFavorites();
    document.querySelectorAll('.btn-fav-toggle').forEach(btn => {
      const id = String(btn.dataset.id);
      btn.classList.toggle('is-fav', favs.includes(id));
    });
    const favCountBadges = document.querySelectorAll('.fav-count-badge');
    favCountBadges.forEach(badge => {
      badge.textContent = favs.length;
    });
  }

  document.addEventListener('click', e => {
    const favBtn = e.target.closest('.btn-fav-toggle');
    if (favBtn) {
      e.preventDefault();
      e.stopPropagation();
      const id = favBtn.dataset.id;
      toggleFavorite(id);
    }
  });

  updateFavoritesUI();

  // =========================================================================
  // 4. Interactive Recipe Detail Modal with Portion Scaler
  // =========================================================================
  let originalIngredients = [];
  let baseServings = 4;
  let currentServings = 4;

  const rmModal = document.getElementById('recipeModal');
  if (rmModal) {
    document.querySelectorAll('.view-recipe').forEach(btn => {
      btn.addEventListener('click', () => {
        const d = btn.dataset;
        
        // Header & Media
        const imgEl = document.getElementById('rmImage');
        if (imgEl) {
          imgEl.src = d.image || 'images/hero.jpg';
          imgEl.onerror = () => { imgEl.src = 'images/hero.jpg'; };
        }
        
        const titleEl = document.getElementById('rmTitle');
        if (titleEl) titleEl.textContent = d.title || '';
        
        const catEl = document.getElementById('rmCategory');
        if (catEl) catEl.textContent = d.category || '';
        
        const descEl = document.getElementById('rmDescription');
        if (descEl) descEl.textContent = d.description || '';

        // Ribbons
        const prepEl = document.getElementById('rmPrep');
        if (prepEl) prepEl.textContent = d.prep || '15 mins';

        const cookEl = document.getElementById('rmCook');
        if (cookEl) cookEl.textContent = d.cook || '25 mins';

        const diffEl = document.getElementById('rmDifficulty');
        if (diffEl) diffEl.textContent = d.difficulty || 'Easy';

        const spiceEl = document.getElementById('rmSpice');
        if (spiceEl) spiceEl.textContent = d.spice || 'Medium';

        // Servings & Scaler
        const matchServings = (d.servings || '4').match(/\d+/);
        baseServings = matchServings ? parseInt(matchServings[0], 10) : 4;
        currentServings = baseServings;
        updateScalerDisplay();

        // Ingredients
        originalIngredients = (d.ingredients || '')
          .split('\n')
          .map(line => line.trim())
          .filter(Boolean);
        renderIngredients();

        // Instructions
        const stepsEl = document.getElementById('rmSteps');
        if (stepsEl) {
          stepsEl.innerHTML = '';
          const steps = (d.instructions || '')
            .split('\n')
            .map(line => line.trim())
            .filter(Boolean);

          steps.forEach((step, idx) => {
            const cleanText = step.replace(/^\d+[\.\)]\s*/, '');
            const li = document.createElement('li');
            li.innerHTML = `
              <div class="step-num-badge">${idx + 1}</div>
              <div class="step-text flex-grow-1">${cleanText}</div>
            `;
            li.addEventListener('click', () => {
              li.classList.toggle('step-completed');
            });
            stepsEl.appendChild(li);
          });
        }
      });
    });

    // Scaler buttons
    const scalerMinus = document.getElementById('scalerMinus');
    const scalerPlus = document.getElementById('scalerPlus');

    if (scalerMinus && scalerPlus) {
      scalerMinus.addEventListener('click', () => {
        if (currentServings > 1) {
          currentServings -= 1;
          updateScalerDisplay();
          renderIngredients();
        }
      });

      scalerPlus.addEventListener('click', () => {
        if (currentServings < 24) {
          currentServings += 1;
          updateScalerDisplay();
          renderIngredients();
        }
      });
    }

    function updateScalerDisplay() {
      const valEl = document.getElementById('scalerValue');
      if (valEl) valEl.textContent = currentServings;
    }

    function scaleQuantity(line, factor) {
      if (factor === 1) return line;
      // Matches fractions like 1/2, 1 1/2 or numbers like 2, 2.5
      return line.replace(/^(\d+(?:\.\d+)?|\d+\/\d+|\d+\s+\d+\/\d+)/, (match) => {
        try {
          let num = 0;
          if (match.includes('/')) {
            const parts = match.trim().split(/\s+/);
            if (parts.length === 2) {
              const [num1, den] = parts[1].split('/');
              num = parseFloat(parts[0]) + parseFloat(num1) / parseFloat(den);
            } else {
              const [num1, den] = parts[0].split('/');
              num = parseFloat(num1) / parseFloat(den);
            }
          } else {
            num = parseFloat(match);
          }
          const scaled = num * factor;
          return scaled % 1 === 0 ? scaled : scaled.toFixed(1);
        } catch {
          return match;
        }
      });
    }

    function renderIngredients() {
      const ingEl = document.getElementById('rmIngredients');
      if (!ingEl) return;
      ingEl.innerHTML = '';
      const factor = currentServings / baseServings;

      originalIngredients.forEach(item => {
        const scaledText = scaleQuantity(item, factor);
        const li = document.createElement('li');
        li.innerHTML = `
          <span class="custom-checkbox"><i class="bi bi-check"></i></span>
          <span class="ing-text">${scaledText}</span>
        `;
        li.addEventListener('click', () => {
          li.classList.toggle('checked');
        });
        ingEl.appendChild(li);
      });
    }

    // Print & Share in modal
    const btnPrint = document.getElementById('btnPrintRecipe');
    if (btnPrint) {
      btnPrint.addEventListener('click', () => window.print());
    }

    const btnShare = document.getElementById('btnShareRecipe');
    if (btnShare) {
      btnShare.addEventListener('click', () => {
        const title = document.getElementById('rmTitle')?.textContent || 'Lanka Recipe';
        const url = window.location.href.split('#')[0];
        if (navigator.clipboard) {
          navigator.clipboard.writeText(`${title} - Cook it with Lanka Recipe Book: ${url}`);
          showToast('Recipe link copied to clipboard! 📋');
        } else {
          showToast('Copy URL from your browser address bar.');
        }
      });
    }
  }

  // =========================================================================
  // 5. Kitchen Cooking Timer Widget
  // =========================================================================
  let timerInterval = null;
  let remainingSeconds = 600; // 10 minutes default
  let timerRunning = false;

  const timerDisplay = document.getElementById('timerDisplay');
  const timerStartBtn = document.getElementById('timerStartBtn');
  const timerResetBtn = document.getElementById('timerResetBtn');
  const presetBtns = document.querySelectorAll('.timer-preset');

  function updateTimerText() {
    if (!timerDisplay) return;
    const m = Math.floor(remainingSeconds / 60);
    const s = remainingSeconds % 60;
    timerDisplay.textContent = `${String(m).padStart(2, '0')}:${String(s).padStart(2, '0')}`;
  }

  function playChime() {
    try {
      const ctx = new (window.AudioContext || window.webkitAudioContext)();
      const osc = ctx.createOscillator();
      const gain = ctx.createGain();
      osc.type = 'sine';
      osc.frequency.setValueAtTime(587.33, ctx.currentTime); // D5
      osc.frequency.setValueAtTime(880, ctx.currentTime + 0.15); // A5
      gain.gain.setValueAtTime(0.3, ctx.currentTime);
      gain.gain.exponentialRampToValueAtTime(0.001, ctx.currentTime + 0.8);
      osc.connect(gain);
      gain.connect(ctx.destination);
      osc.start();
      osc.stop(ctx.currentTime + 0.8);
    } catch {
      // AudioContext unavailable
    }
  }

  if (timerStartBtn && timerDisplay) {
    updateTimerText();

    timerStartBtn.addEventListener('click', () => {
      if (timerRunning) {
        // Pause
        clearInterval(timerInterval);
        timerRunning = false;
        timerStartBtn.innerHTML = '<i class="bi bi-play-fill me-1"></i>Resume';
        timerStartBtn.classList.remove('btn-warning');
        timerStartBtn.classList.add('btn-emerald');
      } else {
        // Start
        timerRunning = true;
        timerStartBtn.innerHTML = '<i class="bi bi-pause-fill me-1"></i>Pause';
        timerStartBtn.classList.remove('btn-emerald');
        timerStartBtn.classList.add('btn-warning');

        timerInterval = setInterval(() => {
          if (remainingSeconds > 0) {
            remainingSeconds--;
            updateTimerText();
          } else {
            clearInterval(timerInterval);
            timerRunning = false;
            timerStartBtn.innerHTML = '<i class="bi bi-play-fill me-1"></i>Start Timer';
            timerStartBtn.classList.remove('btn-warning');
            timerStartBtn.classList.add('btn-emerald');
            playChime();
            showToast('⏰ Kitchen Timer Done! Check your dish.');
          }
        }, 1000);
      }
    });

    if (timerResetBtn) {
      timerResetBtn.addEventListener('click', () => {
        clearInterval(timerInterval);
        timerRunning = false;
        const activePreset = document.querySelector('.timer-preset.active');
        const min = activePreset ? parseInt(activePreset.dataset.min, 10) : 10;
        remainingSeconds = min * 60;
        updateTimerText();
        timerStartBtn.innerHTML = '<i class="bi bi-play-fill me-1"></i>Start Timer';
        timerStartBtn.classList.remove('btn-warning');
        timerStartBtn.classList.add('btn-emerald');
      });
    }

    presetBtns.forEach(btn => {
      btn.addEventListener('click', () => {
        presetBtns.forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
        clearInterval(timerInterval);
        timerRunning = false;
        const min = parseInt(btn.dataset.min, 10);
        remainingSeconds = min * 60;
        updateTimerText();
        timerStartBtn.innerHTML = '<i class="bi bi-play-fill me-1"></i>Start Timer';
        timerStartBtn.classList.remove('btn-warning');
        timerStartBtn.classList.add('btn-emerald');
      });
    });
  }

  // =========================================================================
  // 6. Live Search & Multi-filter (recipes.php)
  // =========================================================================
  const searchBox = document.getElementById('searchBox');
  const catPills = document.querySelectorAll('.filter-pill-btn');
  const diffFilter = document.getElementById('difficultyFilter');
  const sortSelect = document.getElementById('sortSelect');
  const favFilterBtn = document.getElementById('favFilterBtn');
  const recipeGrid = document.getElementById('recipeGrid');
  const noResults = document.getElementById('noResults');
  const recipeCountEl = document.getElementById('recipeCount');

  let activeCategory = '';
  let showFavsOnly = false;

  function runFilter() {
    if (!recipeGrid) return;
    const query = searchBox ? searchBox.value.toLowerCase().trim() : '';
    const diff = diffFilter ? diffFilter.value.toLowerCase() : '';
    const favs = getFavorites();

    const items = Array.from(recipeGrid.querySelectorAll('.recipe-item'));
    let visibleCount = 0;

    items.forEach(item => {
      const id = String(item.dataset.id);
      const title = item.dataset.title || '';
      const category = item.dataset.category || '';
      const difficulty = (item.dataset.difficulty || '').toLowerCase();

      const matchesQuery = !query || title.includes(query);
      const matchesCategory = !activeCategory || category.toLowerCase() === activeCategory.toLowerCase();
      const matchesDiff = !diff || difficulty === diff;
      const matchesFav = !showFavsOnly || favs.includes(id);

      const isVisible = matchesQuery && matchesCategory && matchesDiff && matchesFav;
      item.classList.toggle('d-none', !isVisible);

      if (isVisible) visibleCount++;
    });

    // Handle Sorting
    if (sortSelect) {
      const sortBy = sortSelect.value;
      const sorted = items.sort((a, b) => {
        if (sortBy === 'title-asc') {
          return a.dataset.title.localeCompare(b.dataset.title);
        } else if (sortBy === 'title-desc') {
          return b.dataset.title.localeCompare(a.dataset.title);
        } else if (sortBy === 'cook-asc') {
          return (parseInt(a.dataset.cooktime, 10) || 0) - (parseInt(b.dataset.cooktime, 10) || 0);
        }
        return 0;
      });
      sorted.forEach(el => recipeGrid.appendChild(el));
    }

    if (noResults) noResults.classList.toggle('d-none', visibleCount > 0);
    if (recipeCountEl) recipeCountEl.textContent = `${visibleCount} recipe${visibleCount === 1 ? '' : 's'}`;
  }

  // Bind category pills
  catPills.forEach(pill => {
    pill.addEventListener('click', () => {
      catPills.forEach(p => p.classList.remove('active'));
      pill.classList.add('active');
      activeCategory = pill.dataset.category || '';
      runFilter();
    });
  });

  if (searchBox) {
    searchBox.addEventListener('input', runFilter);
  }
  if (diffFilter) {
    diffFilter.addEventListener('change', runFilter);
  }
  if (sortSelect) {
    sortSelect.addEventListener('change', runFilter);
  }
  if (favFilterBtn) {
    favFilterBtn.addEventListener('click', () => {
      showFavsOnly = !showFavsOnly;
      favFilterBtn.classList.toggle('active', showFavsOnly);
      runFilter();
    });
  }

  // Preselect category from URL parameter ?cat=...
  if (recipeGrid) {
    const urlParams = new URLSearchParams(window.location.search);
    const catParam = urlParams.get('cat');
    if (catParam) {
      catPills.forEach(pill => {
        if (pill.dataset.category?.toLowerCase() === catParam.toLowerCase()) {
          pill.click();
        }
      });
    }
  }

  // =========================================================================
  // 7. Form Validations & Password Confirmation
  // =========================================================================
  document.querySelectorAll('.needs-validation').forEach(form => {
    form.addEventListener('submit', e => {
      const pw = form.querySelector('#pw');
      const pw2 = form.querySelector('#pw2');
      if (pw && pw2) {
        if (pw.value !== pw2.value) {
          pw2.setCustomValidity('mismatch');
        } else {
          pw2.setCustomValidity('');
        }
      }
      if (!form.checkValidity()) {
        e.preventDefault();
        e.stopPropagation();
      }
      form.classList.add('was-validated');
    });
  });

  // Password visibility toggle
  document.querySelectorAll('.btn-toggle-password').forEach(btn => {
    btn.addEventListener('click', () => {
      const targetInput = document.querySelector(btn.dataset.target);
      if (targetInput) {
        const isPw = targetInput.type === 'password';
        targetInput.type = isPw ? 'text' : 'password';
        const icon = btn.querySelector('i');
        if (icon) {
          icon.className = isPw ? 'bi bi-eye-slash' : 'bi bi-eye';
        }
      }
    });
  });

  // =========================================================================
  // 8. Dashboard Live Recipe Preview & Preset Image Picker
  // =========================================================================
  const presetThumbs = document.querySelectorAll('.preset-img-thumb');
  const imgUrlInput = document.getElementById('recipeImageUrlInput');
  const previewImg = document.getElementById('previewCardImg');
  const previewTitle = document.getElementById('previewCardTitle');
  const previewCat = document.getElementById('previewCardCategory');
  const previewTime = document.getElementById('previewCardTime');
  
  const formTitle = document.getElementById('formRecipeTitle');
  const formCat = document.getElementById('formRecipeCategory');
  const formCook = document.getElementById('formRecipeCookTime');

  if (presetThumbs.length > 0 && imgUrlInput) {
    presetThumbs.forEach(thumb => {
      thumb.addEventListener('click', () => {
        presetThumbs.forEach(t => t.classList.remove('selected'));
        thumb.classList.add('selected');
        imgUrlInput.value = thumb.dataset.src;
        if (previewImg) previewImg.src = thumb.dataset.src;
      });
    });
  }

  if (formTitle && previewTitle) {
    formTitle.addEventListener('input', () => {
      previewTitle.textContent = formTitle.value || 'Your Recipe Title';
    });
  }
  if (formCat && previewCat) {
    formCat.addEventListener('change', () => {
      previewCat.textContent = formCat.value || 'Category';
    });
  }
  if (formCook && previewTime) {
    formCook.addEventListener('input', () => {
      previewTime.textContent = formCook.value || '25 mins';
    });
  }
  if (imgUrlInput && previewImg) {
    imgUrlInput.addEventListener('input', () => {
      previewImg.src = imgUrlInput.value || 'images/hero.jpg';
    });
  }

});
