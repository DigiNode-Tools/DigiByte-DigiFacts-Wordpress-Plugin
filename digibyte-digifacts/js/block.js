wp.blocks.registerBlockType('digibyte/digifacts', {
    title: 'DigiByte DigiFacts',
    icon: 'list-view', // Use a WordPress Dashicon or custom svg
    category: 'widgets',
    edit: function() {
        return wp.element.createElement('div', {}, 'DigiByte DigiFacts will appear here.');
    },
    save: function() {
        return null; // Dynamic blocks do not save content to the post_content.
    }
});
