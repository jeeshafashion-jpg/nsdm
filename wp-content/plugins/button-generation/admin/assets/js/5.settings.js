'use strict';

jQuery(document).ready(function ($) {

    const selectors = {
        settings: '.wpie-settings__main',
        color_picker: '.wpie-color',
        checkbox: '.wpie-field input[type="checkbox"]',
        icon_type: '[data-field="icon_type"]',
        icon_picker: '[data-field="icon"]',
        type: '[data-field="type"]',
        appearance: '[data-field="appearance"]',
        item_type: '[data-field="item_type"]',
        location: '[data-field="location"]',
        rotate_button: '[data-field="rotate_button"]',
        rotate_icon: '[data-field="rotate_icon"]',
        border_style: '[data-field*="border_style"]',
        shadow: '[data-field="shadow"]',
        animation: '[data-field="animation"]',
        badge_enabled: '[data-field="enable_badge"]',
        enable_tracking: '[data-field="enable_tracking"]',
        delete_link: '.wpie-link-delete, .delete a',
        reset_link: '.wpie-link-reset-static',

        custom_icon: '[data-field="item_custom"]',
        text_icon: '[data-field="item_custom_text_check"]',
        item: '.wpie-item',
        item_remove: '.wpie-item_heading .dashicons-trash',
        item_heading: '.wpie-item .wpie-item_heading',

    };


    function set_up() {

        $(selectors.color_picker).wpColorPicker({
            change: function (event, ui) {
                $(selectors.item).wowButtonLiveBuilder();
            },
        });
        if($(selectors.settings).length) {
            $(selectors.settings).wowButtonLiveBuilder();
        }

        $(selectors.checkbox).each(set_checkbox);
        $(selectors.icon_type).each(icon_type);
        $(selectors.type).each(type);
        $(selectors.appearance).each(appearance);
        $(selectors.rotate_button).each(rotate_button);
        $(selectors.rotate_icon).each(rotate_icon);
        $(selectors.item_type).each(item_type);
        $(selectors.location).each(item_location);
        $(selectors.border_style).each(border_style);
        $(selectors.shadow).each(shadow);
        $(selectors.animation).each(animation);
        $(selectors.badge_enabled).each(badge_enabled);
        $(selectors.enable_tracking).each(enable_tracking);

        // $(selectors.custom_icon).each(custom_icon);
        // $(selectors.text_icon).each(custom_icon);
        //
    }

    function initialize_events() {
        $(selectors.settings).on('change', selectors.checkbox, set_checkbox);
        $(selectors.settings).on('change', selectors.icon_type, icon_type);
        $(selectors.settings).on('change', selectors.type, type);
        $(selectors.settings).on('change', selectors.appearance, appearance);
        $(selectors.settings).on('change', selectors.rotate_button, rotate_button);
        $(selectors.settings).on('change', selectors.rotate_icon, rotate_icon);
        $(selectors.settings).on('change', selectors.item_type, item_type);
        $(selectors.settings).on('change', selectors.location, item_location);
        $(selectors.settings).on('change', selectors.border_style, border_style);
        $(selectors.settings).on('change', selectors.shadow, shadow);
        $(selectors.settings).on('change', selectors.animation, animation);
        $(selectors.settings).on('change', selectors.badge_enabled, badge_enabled);
        $(selectors.settings).on('change', selectors.enable_tracking, enable_tracking);
        $(document).on('click', selectors.delete_link, delete_menu);
        $(document).on('click', selectors.reset_link, reset_count);

        // $(selectors.settings).on('change', selectors.custom_icon, custom_icon);
        // $(selectors.settings).on('change', selectors.text_icon, custom_icon);
        // ;
        // $(selectors.settings).on('click', selectors.item_remove, item_remove);
        $(selectors.settings).on('click', selectors.item_heading, item_toggle);


        $(selectors.settings).on('change click keyup', function () {
            $(selectors.item).wowButtonLiveBuilder();
        });
    }

    function initialize() {
        set_up();
        initialize_events();
    }

    // Set the checkboxes
    function set_checkbox() {
        const next = $(this).next('input[type="hidden"]');
        if ($(this).is(':checked')) {
            next.val('1');
        } else {
            next.val('0');
        }
    }

    function icon_type() {
        const type = $(this).val();
        if (type === 'icon') {
            $(selectors.icon_picker).wowIconPicker();
        } else {
            $.iconpicker.batch(selectors.icon_picker, 'destroy');
        }
    }


    // Change the button Type
    function type() {
        const parent = get_parent_fields($(this));
        const box = get_field_box($(this));
        const type = $(this).val();
        const fields = parent.find('[data-field-box]').not(box).not('[data-field-box="standard"]');
        const standardType = parent.find('[data-field-box="standard"]');
        fields.addClass('is-hidden');
        standardType.addClass('is-hidden');
        if (type === 'floating') {
            fields.removeClass('is-hidden');
        }
        if(type === 'standard') {
            standardType.removeClass('is-hidden');
        }
        $(selectors.location).each(item_location);
    }

    function appearance() {
        const parent = get_parent_fields($(this), '.wpie-fieldset');
        const box = get_field_box($(this));
        const type = $(this).val();
        const fields = parent.find('[data-field-box]').not(box).not('[data-field-box="tooltip"]');
        fields.addClass('is-hidden');

        if (type === 'text') {
            fields.filter('[data-field-box="text"]').removeClass('is-hidden');
        }
        if (type === 'icon') {
            fields.filter('[data-field-box="icon"], [data-field-box="rotate_icon"]').removeClass('is-hidden');
        }
        if (type === 'text_icon') {
            fields.removeClass('is-hidden');
        }
    }

    function rotate_button() {
        const type = $(this).val();
        const field = $('[data-field-box="rotate_btn_custom"]');
        field.addClass('is-hidden');
        if (type === 'custom') {
            field.removeClass('is-hidden');
        }
    }

    function rotate_icon() {
        const type = $(this).val();
        const field = $('[data-field-box="rotate_icon_custom"]');
        field.addClass('is-hidden');
        if (type === 'custom') {
            field.removeClass('is-hidden');
        }
    }

    function item_type() {
        const parent = get_parent_fields($(this));
        const box = get_field_box($(this));
        const type = $(this).val();
        const fields = parent.find('[data-field-box]').not(box);
        fields.addClass('is-hidden');

        const linkText = parent.find('[data-field-box="item_link"] .wpie-field__title');
        linkText.text('Link');

        // Mapping menu types to the respective field boxes.
        const typeFieldMapping = {
            link: ['item_link', 'new_tab'],
            share: ['item_share'],
            translate: ['gtranslate'],
            smoothscroll: ['item_link'],
            login: ['item_link'],
            logout: ['item_link'],
            lostpassword: ['item_link'],
            email: ['item_link'],
            telephone: ['item_link'],
            download: ['item_link', 'download'],
        };

        // Customize the link text for certain types
        const linkTextMapping = {
            login: 'Redirect URL',
            logout: 'Redirect URL',
            lostpassword: 'Redirect URL',
            email: 'Email',
            telephone: 'Telephone'
        };

        if (typeFieldMapping[type]) {
            const fieldsToShow = typeFieldMapping[type];
            fieldsToShow.forEach(field => {
                parent.find(`[data-field-box="${field}"]`).removeClass('is-hidden');
            });

            if (linkTextMapping[type])
                linkText.text(linkTextMapping[type]);
        }
    }

    function item_location() {
        const type = $(this).val();
        const fields = $('[data-field-box*="location_"]');
        fields.addClass('is-hidden');

        if($(selectors.type).val() === 'standard') {
            return;
        }

        switch (type) {
            case 'topLeft':
                fields.filter('[data-field-box="location_top"], [data-field-box="location_left"]').removeClass('is-hidden');
                break;
            case 'topCenter':
                fields.filter('[data-field-box="location_top"]').removeClass('is-hidden');
                break;
            case 'topRight':
                fields.filter('[data-field-box="location_top"], [data-field-box="location_right"]').removeClass('is-hidden');
                break;
            case 'bottomLeft':
                fields.filter('[data-field-box="location_bottom"], [data-field-box="location_left"]').removeClass('is-hidden');
                break;
            case 'bottomCenter':
                fields.filter('[data-field-box="location_bottom"]').removeClass('is-hidden');
                break;
            case 'bottomRight':
                fields.filter('[data-field-box="location_bottom"], [data-field-box="location_right"]').removeClass('is-hidden');
                break;
            case 'left':
                fields.filter('[data-field-box="location_left"]').removeClass('is-hidden');
                break;
            case 'right':
                fields.filter('[data-field-box="location_right"]').removeClass('is-hidden');
                break;
        }

    }

    function border_style() {
        const parent = get_parent_fields($(this), '.wpie-fieldset');
        const box = get_field_box($(this));
        const type = $(this).val();
        const fields = parent.find('[data-field-box]').not(box).not('[data-field-box*="border_radius"]');
        fields.addClass('is-hidden');
        if (type !== 'none') {
            fields.removeClass('is-hidden');
        }
    }

    function shadow() {
        const parent = get_parent_fields($(this), '.wpie-fieldset');
        const box = get_field_box($(this));
        const type = $(this).val();
        const fields = parent.find('[data-field-box]').not(box);
        fields.addClass('is-hidden');
        if (type !== 'none') {
            fields.removeClass('is-hidden');
        }
    }

    function animation() {
        const parent = get_parent_fields($(this), '.wpie-fieldset');
        const box = get_field_box($(this));
        const type = $(this).val();
        const fields = parent.find('[data-field-box]').not(box);
        fields.addClass('is-hidden');
        if (type !== 'none') {
            fields.removeClass('is-hidden');
        }
    }

    function effects (){
        const parent = get_parent_fields($(this));
        const box = get_field_box($(this));
        const type = $(this).val();
        const fields = parent.find('[data-field-box]').not(box);
        fields.addClass('is-hidden');
        if (type !== 'none') {
            fields.removeClass('is-hidden');
        }
    }

    function badge_enabled() {
        const badgeFields = $('.wpie-badge-enabled');
        if ($(this).is(':checked')) {
            badgeFields.removeClass('is-hidden');
        } else {
            badgeFields.addClass('is-hidden');
        }
    }

    function custom_icon() {
        const fieldset = get_parent_fields($(this), '.wpie-fieldset');
        const parent_fields = get_parent_fields($(this));
        const neighborhood = fieldset.find('.wpie-fields').not(parent_fields).find('input[type="checkbox"]');
        const box = get_field_box($(this));
        const fields = parent_fields.find('[data-field-box]').not(box);
        fields.addClass('is-hidden');
        if ($(this).is(':checked')) {
            fields.removeClass('is-hidden');
            $(neighborhood).attr('disabled', 'disabled');
        } else {
            $(neighborhood).removeAttr('disabled');
        }
    }

    // Enable Event Tracking
    function enable_tracking() {
        const fieldset = get_parent_fields($(this), '.wpie-fieldset');
        const tracking_field = fieldset.find('.wpie-event-tracking');
        tracking_field.addClass('is-hidden');
        if ($(this).is(':checked')) {
            tracking_field.removeClass('is-hidden');
        }
    }

    function item_remove() {
        const userConfirmed = confirm("Are you sure you want to remove this element?");
        if (userConfirmed) {
            const parent = $(this).closest('.wpie-item');
            $(parent).remove();
        }
    }

    function item_toggle() {
        const parent = get_parent_fields($(this), '.wpie-item');
        const val = $(parent).attr('open') ? '0' : '1';
        $(parent).find('.wpie-item__toggle').val(val);
    }

    function delete_menu(e) {
        const proceed = confirm("Are you sure want to Delete Menu?");
        if (!proceed) {
            e.preventDefault();
        }
    }

    function reset_count(e) {
        const proceed = confirm("Are you sure you want to reset the statistics?");
        if (!proceed) {
            e.preventDefault();
        } else {
            e.preventDefault();
            const href = $(this).attr('href');
            const url = new URL(href);
            const params = url.searchParams;
            let postData = {};
            for (let pair of params.entries()) {
                postData[pair[0]] = pair[1];
            }

            $.post(ajaxurl, postData, function (response) {
                if (response.result === 'OK') {
                    $('#tool_view').val('0');
                    $('#tool_action').val('0');
                    $('#conversion').val('0%');
                }
            });
        }

    }

    function get_parent_fields($el, $class = '.wpie-fields') {
        return $el.closest($class);
    }

    function get_field_box($el, $class = '.wpie-field') {
        return $el.closest($class);
    }

    initialize();
});