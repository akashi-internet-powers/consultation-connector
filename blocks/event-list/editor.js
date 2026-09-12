(function (wp) {
    var __ = wp.i18n.__;

    wp.blocks.registerBlockType('ototsugu-connector/event-list', {
        title: __('相談会日程一覧', 'ototsugu-connector'),
        category: 'widgets',
        icon: 'calendar-alt',
        description: __('相談会の日程一覧をWeb表示する動的ブロック。', 'ototsugu-connector'),
        attributes: {
            layout: {
                type: 'string',
                default: 'list',
            },
            showDetailLink: {
                type: 'boolean',
                default: true,
            },
            dateFormat: {
                type: 'string',
                default: 'full',
            },
            showReservationLink: {
                type: 'boolean',
                default: true,
            },
        },
        supports: {
            html: false,
        },
        edit: function (props) {
            var SelectControl = wp.components.SelectControl;
            var ToggleControl = wp.components.ToggleControl;
            var Fragment = wp.element.Fragment;
            var InspectorControls = wp.blockEditor.InspectorControls;
            var blockProps = wp.blockEditor.useBlockProps({
                className: 'ce-event-list-editor-placeholder',
            });

            return wp.element.createElement(
                Fragment,
                null,
                wp.element.createElement(
                    InspectorControls,
                    null,
                    wp.element.createElement(SelectControl, {
                        label: __('表示形式', 'ototsugu-connector'),
                        value: props.attributes.layout,
                        options: [
                            { label: __('一行表示', 'ototsugu-connector'), value: 'list' },
                            { label: __('表形式', 'ototsugu-connector'), value: 'table' },
                            { label: __('カード形式', 'ototsugu-connector'), value: 'card' },
                        ],
                        onChange: function (layout) {
                            props.setAttributes({ layout: layout });
                        },
                    }),
                    wp.element.createElement(SelectControl, {
                        label: __('日付表示', 'ototsugu-connector'),
                        value: props.attributes.dateFormat,
                        options: [
                            { label: '2026年9月6日（日）', value: 'full' },
                            { label: '2026/09/06（日）', value: 'slash' },
                            { label: '9月6日（日）', value: 'short' },
                        ],
                        onChange: function (dateFormat) {
                            props.setAttributes({ dateFormat: dateFormat });
                        },
                    }),
                    wp.element.createElement(ToggleControl, {
                        label: __('詳細リンクを表示', 'ototsugu-connector'),
                        checked: props.attributes.showDetailLink,
                        onChange: function (showDetailLink) {
                            props.setAttributes({ showDetailLink: showDetailLink });
                        },
                    }),
                    wp.element.createElement(ToggleControl, {
                        label: __('予約URLリンクを表示', 'ototsugu-connector'),
                        checked: props.attributes.showReservationLink,
                        onChange: function (showReservationLink) {
                            props.setAttributes({ showReservationLink: showReservationLink });
                        },
                    })
                ),
                wp.element.createElement(
                    'p',
                    blockProps,
                    __('相談会日程一覧（公開画面で表示されます）', 'ototsugu-connector')
                )
            );
        },
        save: function () {
            return null;
        },
    });
})(window.wp);
