(function( $ ) {
	'use strict';

	function init() {
    const mount = document.getElementById('orphan-images-filter-mount');

    /**
     * Inject dropdown options
     */
    if (mount) {
      const select = document.querySelector('select[name="attachment-filter"]');

      if (select && !select.querySelector('option[value="orphan"]')) {
        const orphan_option = document.createElement('option');
        orphan_option.value = 'orphan';
        orphan_option.textContent = 'Orphan Images';
        select.appendChild(orphan_option);
      }

      if (select && !select.querySelector('option[value="ignored"]')) {
        const ignored_option = document.createElement('option');
        ignored_option.value = 'ignored';
        ignored_option.textContent = 'Ignored Images';
        select.appendChild(ignored_option);
      }

      const params = new URLSearchParams(window.location.search);

      if (params.get('attachment-filter') === 'orphan' && select) {
        select.value = 'orphan';
      }

      if (params.get('attachment-filter') === 'ignored' && select) {
        select.value = 'ignored';
      }
    }

    /**
     * Run the media SQL button
     */
    const btn = document.getElementById('run-sql');

    if (btn && !btn.dataset.bound) {
      btn.dataset.bound = '1';
      btn.addEventListener('click', function () {
				if (!window.OrphanImages || !OrphanImages.ajaxurl) return;

				btn.disabled = true;

				fetch(OrphanImages.ajaxurl, {
					method: 'POST',
					credentials: 'same-origin',
					headers: {
						'Content-Type': 'application/x-www-form-urlencoded'
					},
					body: new URLSearchParams({
						action: 'run_media_sql'
					})
				})
				.then(r => r.json())
				.then(res => {
					if (res.success) {
						setTimeout(() => {
							window.location.reload();
						}, 100);
					}
				})
				.finally(() => {
					btn.disabled = false;
				});
			});
    }
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }

})( jQuery );
