// 1. Form validation (runs before PHP gets the data)
document.querySelectorAll('.needs-validation').forEach(form => {
  form.addEventListener('submit', e => {
    const pw = form.querySelector('#pw'), pw2 = form.querySelector('#pw2');
    if (pw && pw2) pw2.setCustomValidity(pw.value === pw2.value ? '' : 'mismatch');
    if (!form.checkValidity()) { e.preventDefault(); e.stopPropagation(); }
    form.classList.add('was-validated');
  });
});

// 2. Live search + category filter (dynamic content)
const box = document.getElementById('searchBox'), cat = document.getElementById('categoryFilter');
function filterRecipes() {
  const q = box.value.toLowerCase().trim(), c = cat.value;
  let shown = 0;
  document.querySelectorAll('#recipeGrid .recipe-item').forEach(el => {
    const ok = el.dataset.title.includes(q) && (!c || el.dataset.category === c);
    el.classList.toggle('d-none', !ok);
    if (ok) shown++;
  });
  document.getElementById('noResults').classList.toggle('d-none', shown > 0);
}
if (box && cat) { box.addEventListener('input', filterRecipes); cat.addEventListener('change', filterRecipes); }

// 3. Recipe modal (event handling)
document.querySelectorAll('.view-recipe').forEach(btn => {
  btn.addEventListener('click', () => {
    document.getElementById('rmTitle').textContent = btn.dataset.title;
    const fill = (id, text) => {
      const el = document.getElementById(id); el.innerHTML = '';
      text.split('\n').filter(l => l.trim()).forEach(l => {
        const li = document.createElement('li'); li.textContent = l; el.appendChild(li);
      });
    };
    fill('rmIngredients', btn.dataset.ingredients);
    fill('rmSteps', btn.dataset.instructions);
  });
});

// 4. Smooth scrolling for in-page links
document.querySelectorAll('a[href^="#"]').forEach(a => {
  a.addEventListener('click', e => {
    const t = document.querySelector(a.getAttribute('href'));
    if (t) { e.preventDefault(); t.scrollIntoView({ behavior: 'smooth' }); }
  });
});

// 5. Tooltips
document.querySelectorAll('.view-recipe').forEach(el => new bootstrap.Tooltip(el));

// 6. Fade-in animation on scroll
const io = new IntersectionObserver(entries => {
  entries.forEach(en => { if (en.isIntersecting) { en.target.classList.add('show'); io.unobserve(en.target); } });
}, { threshold: 0.15 });
document.querySelectorAll('.fade-in').forEach(el => io.observe(el));
