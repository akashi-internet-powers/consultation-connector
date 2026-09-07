(function (wp) {
    wp.blocks.registerBlockType('consultation-connector/event-list', {
        title: '相談会日程一覧',
        category: 'widgets',
        icon: 'calendar-alt',
        description: '相談会の日程一覧をWeb表示する動的ブロック。',
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
                        label: '表示形式',
                        value: props.attributes.layout,
                        options: [
                            { label: '一行表示', value: 'list' },
                            { label: '表形式', value: 'table' },
                            { label: 'カード形式', value: 'card' },
                        ],
                        onChange: function (layout) {
                            props.setAttributes({ layout: layout });
                        },
                    }),
                    wp.element.createElement(SelectControl, {
                        label: '日付表示',
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
                        label: '詳細リンクを表示',
                        checked: props.attributes.showDetailLink,
                        onChange: function (showDetailLink) {
                            props.setAttributes({ showDetailLink: showDetailLink });
                        },
                    }),
                    wp.element.createElement(ToggleControl, {
                        label: '予約URLリンクを表示',
                        checked: props.attributes.showReservationLink,
                        onChange: function (showReservationLink) {
                            props.setAttributes({ showReservationLink: showReservationLink });
                        },
                    })
                ),
                wp.element.createElement(
                    'p',
                    blockProps,
                    '相談会日程一覧（公開画面で表示されます）'
                )
            );
        },
        save: function () {
            return null;
        },
    });
})(window.wp);
