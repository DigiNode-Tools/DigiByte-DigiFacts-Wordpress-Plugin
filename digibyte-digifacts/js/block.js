wp.blocks.registerBlockType('digibyte/digifacts', {
    title: 'DigiByte DigiFacts',
    icon: 'list-view', // Use a WordPress Dashicon or custom svg
    category: 'widgets',
    edit: function() {
        return wp.element.createElement('div', {}, 'DigiFact will appear here in the editor.');
    },
    save: function() {
        return null; // Dynamic blocks do not save content to the post_content.
    }
});
