console.log('ODA Frontend Script Loaded');

(function () {
  'use strict';

  // Wait for DOM to be ready
  document.addEventListener('DOMContentLoaded', function () {
    // Get all buttons that have nested ul elements
    const menuButtons = document.querySelectorAll('ul > li > button');

    menuButtons.forEach(function (button) {
      const listItem = button.parentElement;
      const nestedList = listItem.querySelector('ul');

      // Only add click handlers if there's a nested list
      if (nestedList) {
        // Add the id to the nested list based on aria-labelledby
        const ariaLabelledBy = button.getAttribute('aria-labelledby');
        if (ariaLabelledBy) {
          nestedList.setAttribute('id', ariaLabelledBy);
        }

        // Hide the nested list initially
        nestedList.style.display = 'none';

        // Add click handler to the button
        button.addEventListener('click', function (e) {
          e.stopPropagation();

          // Check if this menu is currently open
          const isOpen = nestedList.style.display === 'block';

          // Close all menus and remove active class from all buttons
          menuButtons.forEach(function (otherButton) {
            const otherListItem = otherButton.parentElement;
            const otherNestedList = otherListItem.querySelector('ul');
            if (otherNestedList) {
              otherNestedList.style.display = 'none';
            }
            otherButton.classList.remove('active');
          });

          // Toggle this menu - if it was closed, open it
          if (!isOpen) {
            nestedList.style.display = 'block';
            button.classList.add('active');
          }
        });
      }
    });

    // Close all menus when clicking anywhere else on the page
    document.addEventListener('click', function (e) {
      // Check if the click was outside all menu buttons and nested lists
      const clickedInsideMenu = e.target.closest('ul > li > button') || e.target.closest('ul > li > ul');

      if (!clickedInsideMenu) {
        menuButtons.forEach(function (button) {
          const listItem = button.parentElement;
          const nestedList = listItem.querySelector('ul');
          if (nestedList) {
            nestedList.style.display = 'none';
          }
          button.classList.remove('active');
        });
      }
    });
  });
})();
