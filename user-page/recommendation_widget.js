/**
 * Shivis Stylevana - Recommendation Widget (Slider Integrated)
 * ==========================================================
 * Apni PHP site mein yeh ek line add karo jahaan bhi 
 * recommendations dikhani ho:
 * 
 *   <div id="sv-recommendations"></div>
 *   <script src="recommendation_widget.js"></script>
 *   <script>
 *     StylevanaRec.init({
 *       userEmail:  '<?= $_SESSION["user_email"] ?>',
 *       currentPid: '<?= $product_id ?>',
 *       title:      'Aapke liye Recommendations',
 *       limit:      10
 *     });
 *   </script
 */

var StylevanaRec = StylevanaRec || (function () {

  // ============================================================
  // CONFIG — Yahan apna Flask server URL daalo
  // ============================================================
  const API_BASE = 'http://localhost:5000';

  // ============================================================
  // CSS inject karo (ek baar) — Slider Styles Added
  // ============================================================
  function injectStyles() {
    if (document.getElementById('sv-rec-styles')) return;
    const style = document.createElement('style');
    style.id = 'sv-rec-styles';
    style.textContent = `
      .sv-rec-section { margin: 40px 0; font-family: inherit; position: relative; }
      .sv-rec-heading { font-size: 1.25rem; font-weight: 700; color: #333; margin-bottom: 4px; }
      .sv-rec-sub     { font-size: 0.82rem; color: #999; margin-bottom: 18px; }
      
      /* Swiper Specific Container */
      .swiper-container-rec { overflow: hidden; position: relative; padding: 10px 5px 30px; }
      
      .sv-rec-card {
        background: #fff;
        border: 1px solid #eee;
        border-radius: 14px;
        overflow: hidden;
        transition: transform .2s, box-shadow .2s;
        text-decoration: none;
        color: inherit;
        display: block;
        height: 100%;
      }
      .sv-rec-card:hover { transform: translateY(-5px); box-shadow: 0 10px 28px rgba(0,0,0,.09); }
      .sv-rec-img-wrap { position: relative; width: 100%; aspect-ratio: 3/4; background: #f9f0f5; overflow: hidden; }
      .sv-rec-img-wrap img { width: 100%; height: 100%; object-fit: cover; }
      .sv-rec-placeholder { width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; font-size: 2.5rem; color: #e8b4cc; }
      .sv-rec-badge {
        position: absolute; top: 8px; left: 8px;
        background: #c0396e; color: #fff;
        font-size: 0.65rem; font-weight: 700; border-radius: 50px;
        padding: 2px 8px; letter-spacing: .3px; z-index: 2;
      }
      .sv-rec-discount {
        position: absolute; top: 8px; right: 8px;
        background: #22a86e; color: #fff;
        font-size: 0.65rem; font-weight: 700; border-radius: 50px;
        padding: 2px 7px; z-index: 2;
      }
      .sv-rec-body   { padding: 10px 12px 13px; }
      .sv-rec-name   { font-size: 0.81rem; font-weight: 600; color: #333; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; margin-bottom: 3px; }
      .sv-rec-cat    { font-size: 0.71rem; color: #aaa; text-transform: capitalize; margin-bottom: 5px; }
      .sv-rec-prices { display: flex; align-items: center; gap: 6px; }
      .sv-rec-price  { font-size: 0.9rem; color: #c0396e; font-weight: 700; }
      .sv-rec-orig   { font-size: 0.75rem; color: #bbb; text-decoration: line-through; }
      .sv-rec-stars  { font-size: 0.72rem; color: #f5a623; margin-top: 4px; }
      .sv-rec-loading { color: #ccc; font-size: 0.9rem; padding: 30px 0; text-align: center; }
      .sv-rec-empty   { color: #bbb; font-size: 0.88rem; padding: 20px 0; }

      /* Arrow overrides */
      .swiper-button-next, .swiper-button-prev { color: #c0396e !important; transform: scale(0.7); }
    `;
    document.head.appendChild(style);
  }

  // ============================================================
  // Fetch helper
  // ============================================================
  async function fetchJSON(url) {
    const res = await fetch(url);
    if (!res.ok) throw new Error('API error: ' + res.status);
    return res.json();
  }

  // ============================================================
  // Discount % calculate karo
  // ============================================================
  function discountPct(original, current) {
    if (!original || original <= current) return 0;
    return Math.round(((original - current) / original) * 100);
  }

  // ============================================================
  // Stars render karo
  // ============================================================
  function stars(rating) {
    if (!rating) return '';
    const full  = Math.round(rating);
    const empty = 5 - full;
    return '★'.repeat(full) + '☆'.repeat(empty) + ' ' + parseFloat(rating).toFixed(1);
  }

  // ============================================================
  // Ek product card HTML (Swiper Slide wrapper ke saath)
  // ============================================================
  function renderCard(p, showScore) {
    const disc     = discountPct(p.original_price, p.productprice);
    const badgeHtml = p.rec_score > 20
      ? `<span class="sv-rec-badge">⭐ Top Pick</span>`
      : p.is_featured
      ? `<span class="sv-rec-badge">✨ Featured</span>`
      : '';
    const discHtml  = disc > 0 ? `<span class="sv-rec-discount">-${disc}%</span>` : '';
    const origHtml  = p.original_price > p.productprice
      ? `<span class="sv-rec-orig">₹${p.original_price.toLocaleString('en-IN')}</span>`
      : '';
    const imgHtml   = p.productphoto
      ? `<img src="${p.productphoto}" alt="${p.productname}" loading="lazy"
              onerror="this.style.display='none';this.nextElementSibling.style.display='flex'">`
        + `<div class="sv-rec-placeholder" style="display:none">👗</div>`
      : `<div class="sv-rec-placeholder">👗</div>`;
    const starsHtml = p.avg_rating ? `<div class="sv-rec-stars">${stars(p.avg_rating)}</div>` : '';
    const scoreNote = (showScore && p.rec_score > 0)
      ? `<div style="font-size:.68rem;color:#ddd;margin-top:3px">score: ${p.rec_score}</div>`
      : '';

    return `
      <div class="swiper-slide">
        <a class="sv-rec-card" href="order.php?pid=${p.pid}" 
           onclick="StylevanaRec.trackClick('${p.pid}')">
          <div class="sv-rec-img-wrap">
            ${imgHtml}
            ${badgeHtml}
            ${discHtml}
          </div>
          <div class="sv-rec-body">
            <div class="sv-rec-name">${p.productname}</div>
            <div class="sv-rec-cat">${p.category || ''}</div>
            <div class="sv-rec-prices">
              <span class="sv-rec-price">₹${p.productprice.toLocaleString('en-IN')}</span>
              ${origHtml}
            </div>
            ${starsHtml}
            ${scoreNote}
          </div>
        </a>
      </div>`;
  }

  // ============================================================
  // Slider render karo
  // ============================================================
  function renderSlider(container, products, showScore, gridId) {
    if (!products || products.length === 0) {
      container.innerHTML = '<p class="sv-rec-empty">Abhi koi recommendation nahi hai. Thoda aur browse karo!</p>';
      return;
    }

    container.innerHTML = `
      <div class="swiper swiper-container-rec" id="swiper-${gridId}">
        <div class="swiper-wrapper">
          ${products.map(p => renderCard(p, showScore)).join('')}
        </div>
        <div class="swiper-button-next"></div>
        <div class="swiper-button-prev"></div>
        <div class="swiper-pagination"></div>
      </div>
    `;

    // Initialize Swiper
    new Swiper(`#swiper-${gridId}`, {
      slidesPerView: 2,
      spaceBetween: 15,
      navigation: { nextEl: '.swiper-button-next', prevEl: '.swiper-button-prev' },
      pagination: { el: '.swiper-pagination', clickable: true },
      breakpoints: {
        640: { slidesPerView: 3 },
        1024: { slidesPerView: 5 },
        1200: { slidesPerView: 6 }
      }
    });
  }

  // ============================================================
  // PUBLIC: init() — Personalized Recommendations
  // ============================================================
  async function init(options) {
    options = options || {};
    const containerId = options.containerId || 'sv-recommendations';
    const container   = document.getElementById(containerId);
    if (!container) return;

    injectStyles();

    const title    = options.title   || 'Aapke liye Recommendations';
    const subtitle = options.subtitle || 'Aapki pasand ke hisaab se';
    const limit    = options.limit   || 8;
    const email    = options.userEmail || '';
    const pid      = options.currentPid || '';
    const showScore = options.showScore || false;

    container.innerHTML = `
      <div class="sv-rec-section">
        <div class="sv-rec-heading">${title}</div>
        <div class="sv-rec-sub">${subtitle}</div>
        <div id="${containerId}-content">
          <p class="sv-rec-loading">⏳ Loading recommendations...</p>
        </div>
      </div>`;

    const contentDiv = document.getElementById(`${containerId}-content`);

    try {
      let url = '';
      if (email) {
        url = `${API_BASE}/recommend?email=${encodeURIComponent(email)}&limit=${limit}`;
        if (pid) url += `&pid=${encodeURIComponent(pid)}`;
      } else {
        url = `${API_BASE}/popular?limit=${limit}`;
      }

      const data = await fetchJSON(url);
      const products = data.recommendations || data.popular || [];
      renderSlider(contentDiv, products, showScore, containerId);
    } catch (err) {
      contentDiv.innerHTML = `<p class="sv-rec-empty">Recommendations load nahi ho sakin. Server check karo.</p>`;
      console.error('StylevanaRec error:', err);
    }
  }

  // ============================================================
  // PUBLIC: initSimilar() — Similar Products
  // ============================================================
  async function initSimilar(options) {
    options = options || {};
    const containerId = options.containerId || 'sv-similar';
    const container   = document.getElementById(containerId);
    if (!container) return;

    injectStyles();

    const title = options.title || 'Aisi aur cheezein';
    const limit = options.limit || 6;
    const pid   = options.currentPid || '';

    if (!pid) return;

    container.innerHTML = `
      <div class="sv-rec-section">
        <div class="sv-rec-heading">${title}</div>
        <div id="${containerId}-content">
          <p class="sv-rec-loading">⏳ Loading...</p>
        </div>
      </div>`;

    const contentDiv = document.getElementById(`${containerId}-content`);

    try {
      const data = await fetchJSON(`${API_BASE}/similar?pid=${encodeURIComponent(pid)}&limit=${limit}`);
      renderSlider(contentDiv, data.similar || [], false, containerId);
    } catch (err) {
      contentDiv.innerHTML = '';
      console.error('StylevanaRec similar error:', err);
    }
  }

  // ============================================================
  // PUBLIC: trackClick()
  // ============================================================
  function trackClick(pid) {
    console.log('Rec click tracked: pid =', pid);
  }

  return { init, initSimilar, trackClick };

})();