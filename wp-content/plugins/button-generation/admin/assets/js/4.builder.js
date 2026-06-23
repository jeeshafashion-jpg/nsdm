'use strict';
(function ($) {

    $.fn.wowButtonLiveBuilder = function () {
        const builder = $('#button-preview');
        const style = $('#wpie-live-preview-style');
        let selectors = {
            appearance: '[data-field="appearance"]',
            text: '[data-field="text"]',
            icon_type: '[data-field="icon_type"]',
            icon: '[data-field="icon"]',
            gap: '[data-field="gap"]',
            badge: '[data-field="enable_badge"]',
            badge_content: '[data-field="badge_content"]',
            tooltip: '[data-field="tooltip_enable"]',
            tooltip_location: '[data-field="tooltip_location"]',
            tooltip_text: '[data-field="tooltip"]',
            rotate_button: '[data-field="rotate_button"]',
            rotate_btn_custom: '[data-field="rotate_btn_custom"]',
            rotate_icon: '[data-field="rotate_icon"]',
            rotate_icon_custom: '[data-field="rotate_icon_custom"]',
            text_location: '[data-field="text_location"]',
            width: '[data-field="width"]',
            height: '[data-field="height"]',
            color: '[data-field="color"]',
            background: '[data-field="background"]',
            hover_color: '[data-field="hover_color"]',
            hover_background: '[data-field="hover_background"]',
            transition_duration: '[data-field="transition_duration"]',
            transition_function: '[data-field="transition_function"]',
            border_radius: '[data-field="border_radius"]',
            border_style: '[data-field="border_style"]',
            border_color: '[data-field="border_color"]',
            border_width: '[data-field="border_width"]',
            shadow: '[data-field="shadow"]',
            shadow_h_offset: '[data-field="shadow_h_offset"]',
            shadow_v_offset: '[data-field="shadow_v_offset"]',
            shadow_blur: '[data-field="shadow_blur"]',
            shadow_spread: '[data-field="shadow_spread"]',
            shadow_color: '[data-field="shadow_color"]',
            icon_size: '[data-field="icon_size"]',
            icon_color: '[data-field="icon_color"]',
            icon_hover_color: '[data-field="icon_hover_color"]',
            font_size: '[data-field="font_size"]',
            font_family: '[data-field="font_family"]',
            font_weight: '[data-field="font_weight"]',
            font_style: '[data-field="font_style"]',
            badge_width: '[data-field="badge_width"]',
            badge_height: '[data-field="badge_height"]',
            badge_color: '[data-field="badge_color"]',
            badge_background: '[data-field="badge_background"]',
            badge_position_top: '[data-field="badge_position_top"]',
            badge_position_right: '[data-field="badge_position_right"]',
            badge_border_radius: '[data-field="badge_border_radius"]',
            badge_border_style: '[data-field="badge_border_style"]',
            badge_border_color: '[data-field="badge_border_color"]',
            badge_border_width: '[data-field="badge_border_width"]',
            badge_font_size: '[data-field="badge_font_size"]',
            badge_font_family: '[data-field="badge_font_family"]',
            badge_font_weight: '[data-field="badge_font_weight"]',
            badge_font_style: '[data-field="badge_font_style"]',
            animation: '[data-field="animation"]',
            animation_duration: '[data-field="animation_duration"]',
            animation_count: '[data-field="animation_count"]',
            animation_delay: '[data-field="animation_delay"]',
            hover_effects: '[data-field="hover_effects"]',
            background_effect: '[data-field="background_effect"]',
            icon_effect: '[data-field="icon_effect"]',
            border_effect: '[data-field="border_effect"]',


        };

        let animation = $(selectors.animation).val();
        let hover_effects = $(selectors.hover_effects).val();
        let background_effect = $(selectors.background_effect).val();
        let icon_effect = $(selectors.icon_effect).val();
        let border_effect = $(selectors.border_effect).val();
        let extra_class = '';

        if(animation !== 'none') {
            extra_class += ' btn-animation ' + animation;
        }
        if(hover_effects !== 'none') {
            extra_class += ' ' + hover_effects;
        }

        if(background_effect !== 'none') {
            extra_class += ' ' + background_effect;
        }

        if(icon_effect !== 'none') {
            extra_class += ' ' + icon_effect;
        }

        if(border_effect !== 'none') {
            extra_class += ' ' + border_effect;
        }

        let template = `<button class="btg-button${extra_class}">{{text}}{{icon}}{{badge}}{{tooltip}}</button>`;
        const type = $(selectors.appearance).val();
        let text = get_text();
        template = template.replace('{{text}}', text);
        let icon = get_icon();
        template = template.replace('{{icon}}', icon);
        let badge = get_badge();
        template = template.replace('{{badge}}', badge);
        let tooltip = get_tooltip();
        template = template.replace('{{tooltip}}', tooltip);

        let css = get_btn_css();
        css += get_badge_css();
        css += get_icon_css();
        css += get_animation();

        let cleanContent = DOMPurify.sanitize(template);
        builder.html(cleanContent);
        style.html(css);

        function get_text() {
            if (type !== 'icon') {
                let text = $(selectors.text).val();
                text = text.replace(/\\n/g, "<br />");
                return text;
            }
            return '';
        }


        function get_animation() {
            let css = '.btg-button.btn-animation {';
            if ($(selectors.animation_count).val() === '0') {
                css += `--count:  infinite;`;
            } else {
                css += `--count:  ${$(selectors.animation_count).val()};`;
            }

            css += `--duration:  ${$(selectors.animation_duration).val()}s;`;
            css += `--delay:  ${$(selectors.animation_delay).val()}s;`;

            css += '}';
            return css;
        }

        function get_icon() {
            if (type === 'text') {
                return '';
            }
            const iconType = $(selectors.icon_type).val();
            const iconVal = $(selectors.icon).val();

            let rotate = '';
            if ($(selectors.rotate_icon).val() !== '' && $(selectors.rotate_icon).val() !== 'custom') {
                rotate = $(selectors.rotate_icon).val();
            }


            if (iconType === 'icon') {
                return `<span class="${iconVal} btg-icon hvr-icon ${rotate}"></span>`;
            }

            if (iconType === 'class') {
                return `<span class="dashicons dashicons-format-image btg-icon hvr-icon ${rotate}"></span>`;
            }

            if (iconType === 'img') {
                return `<img src="${iconVal}" class="btg-icon hvr-icon ${rotate}">`;
            }

            if (iconType === 'emoji') {
                return `<span class="btg-icon hvr-icon ${rotate}">${iconVal}</span>`;
            }

            return '';

        }

        function get_badge() {
            if (!$(selectors.badge).is(':checked')) {
                return '';
            }
            const content = $(selectors.badge_content).val();

            return `<span class="badge">${content}</span>`;

        }

        function get_tooltip() {
            if (!$(selectors.tooltip).is(':checked')) {
                return '';
            }

            const text = $(selectors.tooltip_text).val();
            const outText = text.replace(/\\n/g, "<br/>");
            const location = $(selectors.tooltip_location).val();

            return `<span class="btn-tooltiptext tooltip-${location}">${outText}</span>`;

        }

        function get_icon_css() {
            let css = '.btg-button .btg-icon {';
            if ($(selectors.rotate_icon).val() === 'custom') {
                css += `--rotate:  ${$(selectors.rotate_icon_custom).val()}deg;`;
            }
            css += `--font-size:  ${$(selectors.icon_size).val()}px;`;
            css += `--color:  ${$(selectors.icon_color).val()};`;
            css += '}';

            css += '.btg-button img.btg-icon {';
            if ($(selectors.rotate_icon).val() === 'custom') {
                css += `--rotate:  ${$(selectors.rotate_icon_custom).val()}deg;`;
            }
            css += `--font-size:  ${$(selectors.icon_size).val()}px;`;
            css += '}';
            return css;
        }

        function get_btn_css() {
            let css = '.btg-button {';
            css += `--width:  ${$(selectors.width).val()};`;
            css += `--height:  ${$(selectors.height).val()};`;
            if ($(selectors.rotate_button).val() !== 'none' && $(selectors.rotate_button).val() !== 'custom') {
                css += `--rotate:  ${$(selectors.rotate_button).val()};`;
            }
            if ($(selectors.rotate_button).val() === 'custom') {
                css += `--rotate:  ${$(selectors.rotate_btn_custom).val()}deg;`;
            }
            css += `--direction:  ${$(selectors.text_location).val()};`;
            css += `--gap:  ${$(selectors.gap).val()}px;`;
            css += `--color:  ${$(selectors.color).val()};`;
            css += `--background:  ${$(selectors.background).val()};`;
            css += `--hover-color:  ${$(selectors.hover_color).val()};`;
            css += `--hover-background:  ${$(selectors.hover_background).val()};`;
            css += `--icon-hover-color:  ${$(selectors.icon_hover_color).val()};`;
            css += `--transition-duration:  ${$(selectors.transition_duration).val()}s;`;
            css += `--transition-function:  ${$(selectors.transition_function).val()};`;
            css += `--radius:  ${$(selectors.border_radius).val()};`;
            css += `--border-style:  ${$(selectors.border_style).val()};`;
            css += `--border-color:  ${$(selectors.border_color).val()};`;
            css += `--border-width:  ${$(selectors.border_width).val()}px;`;
            css += `--font-size:  ${$(selectors.font_size).val()}px;`;
            css += `--font-family:  ${$(selectors.font_family).val()};`;
            css += `--font-weight:  ${$(selectors.font_weight).val()};`;
            css += `--font-style:  ${$(selectors.font_style).val()};`;
            if ($(selectors.shadow).val() === 'outset') {
                css += `--shadow:  ${$(selectors.shadow_h_offset).val()}px ${$(selectors.shadow_v_offset).val()}px ${$(selectors.shadow_blur).val()}px ${$(selectors.shadow_spread).val()}px ${$(selectors.shadow_color).val()};`;
            }
            if ($(selectors.shadow).val() === 'inset') {
                css += `--shadow: inset ${$(selectors.shadow_h_offset).val()}px ${$(selectors.shadow_v_offset).val()}px ${$(selectors.shadow_blur).val()}px ${$(selectors.shadow_spread).val()}px ${$(selectors.shadow_color).val()};`;
            }
            css += '}';
            return css;
        }

        function get_badge_css() {
            let css = '.btg-button .badge{';
            css += `--width:  ${$(selectors.badge_width).val()};`;
            css += `--height:  ${$(selectors.badge_height).val()};`;
            css += `--color:  ${$(selectors.badge_color).val()};`;
            css += `--background:  ${$(selectors.badge_background).val()};`;
            css += `--radius:  ${$(selectors.badge_border_radius).val()};`;
            css += `--border-style:  ${$(selectors.badge_border_style).val()};`;
            css += `--border-color:  ${$(selectors.badge_border_color).val()};`;
            css += `--border-width:  ${$(selectors.badge_border_width).val()}px;`;
            css += `--font-size:  ${$(selectors.badge_font_size).val()}px;`;
            css += `--font-family:  ${$(selectors.badge_font_family).val()};`;
            css += `--font-weight:  ${$(selectors.badge_font_weight).val()};`;
            css += `--font-style:  ${$(selectors.badge_font_style).val()};`;
            css += `--top:  ${$(selectors.badge_position_top).val()}px;`;
            css += `--right:  ${$(selectors.badge_position_right).val()}px;`;
            css += '}';
            return css;
        }

        function isValidURL(string) {
            const regex = new RegExp(
                '^(https?:\\/\\/)?' + // protocol
                '((([a-z\\d]([a-z\\d-]*[a-z\\d])*)\\.)+[a-z]{2,}|' + // domain name
                '((\\d{1,3}\\.){3}\\d{1,3}))' + // OR ip (v4) address
                '(\\:\\d+)?(\\/[-a-z\\d%_.~+]*)*' + // port and path
                '(\\?[;&a-z\\d%_.~+=-]*)?' + // query string
                '(\\#[-a-z\\d_]*)?$', 'i'); // fragment locator
            return !!regex.test(string);
        }
    }

}(jQuery));