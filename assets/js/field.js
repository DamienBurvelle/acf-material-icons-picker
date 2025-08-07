/**
 * Included when material_icons_picker fields are rendered for editing by publishers.
 */
//  ( function( $ ) {
// 	function initialize_field( $field ) {
// 		/**
// 		 * $field is a jQuery object wrapping field elements in the editor.
// 		 */
// 		console.log( 'material_icons_picker field initialized' );
// 	}

// 	if( typeof acf.add_action !== 'undefined' ) {
// 		/**
// 		 * Run initialize_field when existing fields of this type load,
// 		 * or when new fields are appended via repeaters or similar.
// 		 */
// 		acf.add_action( 'ready_field/type=material_icons_picker', initialize_field );
// 		acf.add_action( 'append_field/type=material_icons_picker', initialize_field );
// 	}
// } )( jQuery );


jQuery( function( $ ) {
  
  console.log( 'material_icons_picker field initialized' );
  $('.acf-material-icon-wrapper').each(function () {
    console.log('fez');

      const $wrapper = $(this);
      const $iconInput = $wrapper.find('.material-icon-value');
      const $styleInput = $wrapper.find('.material-icon-style');
      const $popover = $wrapper.find('.acf-icon-popover');


      function updatePreview(icon, style) {
        const styleSuffix = (style !== 'filled') ? '-' + style : '';
        const $previewIcon = $wrapper.find('.icon-preview-icon');
        const $clearButton = $wrapper.find('.icon-clear-button');

        $previewIcon
            .attr('class', 'icon-preview-icon material-icons' + styleSuffix)
            .text(icon || 'add')
            .data('style', style);

        // Affiche ou masque le bouton "effacer"
        if (icon) {
            $clearButton.show();
        } else {
            $clearButton.hide();
            $previewIcon.attr('class', 'icon-preview-icon material-icons icon-preview-icon-add');
        }
    }

    // 🟣 Ouvrir/Fermer le popover
    $wrapper.find('.icon-preview-toggle').on('click', function (e) {
      e.stopPropagation();
      $('.acf-icon-popover').not($popover).hide(); // Ferme les autres
      $popover.toggle();
    });
      
      // Tab switching
      $wrapper.find('.tab-button').on('click', function () {
        const style = $(this).data('style');

        // Onglets
        $wrapper.find('.tab-button').removeClass('active');
        $(this).addClass('active');

        // Grilles
        $wrapper.find('.icon-grid').removeClass('active');
        $wrapper.find('.grid-' + style).addClass('active');

        // MAJ champ style
        $styleInput.val(style);
      });

    // Sélection d’icône
    $wrapper.find('.material-icon-option').on('click', function () {
      const $this = $(this);
      const icon = $this.data('icon');
      const style = $this.data('style');

			console.log(style);

      // MAJ champs
      $wrapper.find('.material-icon-option').removeClass('selected');
      $this.addClass('selected');

      $iconInput.val(icon);
      $styleInput.val(style);

      // MAJ aperçu
      updatePreview(icon, style);
      
      $popover.hide(); // Ferme après sélection     
    });

    // 🔍 Recherche dans chaque grille
    $wrapper.find('.acf-material-icon-search').on('input', function () {
      const term = $(this).val().toLowerCase();
      const $grid = $(this).closest('.icon-grid');

      $grid.find('.material-icon-option').each(function () {
          const name = String($(this).data('name') || '').toLowerCase();
          const match = name.includes(term);
          $(this).toggle(match);
      });
    });

    // clear button
    $wrapper.find('.icon-clear-button').on('click', function (e) {
      e.stopPropagation(); // empêche d’ouvrir le popover
  
      // Reset values
      $iconInput.val('');
      $styleInput.val('filled');
  
      // Retire toute sélection dans les grilles
      $wrapper.find('.material-icon-option').removeClass('selected');
  
      // Met à jour l'aperçu
      updatePreview('', 'filled');

      $(this).hide();
  
      // Réinitialise les tabs et grilles
      $wrapper.find('.tab-button').removeClass('active');
      $wrapper.find('.tab-button[data-style="filled"]').addClass('active');
      $wrapper.find('.icon-grid').removeClass('active');
      $wrapper.find('.grid-filled').addClass('active');
  });

    // ⛔ Fermer popover si clic à l’extérieur
    $(document).on('click', function () {
        $popover.hide();
    });

    // Empêcher fermeture sur clic interne
    $popover.on('click', function (e) {
        e.stopPropagation();
    });

  });
});