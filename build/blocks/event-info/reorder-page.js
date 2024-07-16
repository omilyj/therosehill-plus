/******/ (() => { // webpackBootstrap
var __webpack_exports__ = {};
/*!***********************************************!*\
  !*** ./src/blocks/event-info/reorder-page.js ***!
  \***********************************************/
document.addEventListener('DOMContentLoaded', function () {
  let originalParent, originalNextSibling;

  // Function to store the original location
  function storeOriginalLocation() {
    const eventInfo = document.querySelector('.wp-block-therosehill-plus-event-info');
    // Store the original parent and the next sibling
    originalParent = eventInfo.parentNode;
    originalNextSibling = eventInfo.nextSibling;
  }
  function moveEventInfo() {
    const eventInfo = document.querySelector('.wp-block-therosehill-plus-event-info');
    const postTitle = document.querySelector('.rh-single-event-title');
    if (window.innerWidth <= 768) {
      if (eventInfo && postTitle && postTitle.parentNode) {
        // Move event info to be just below the post title
        postTitle.parentNode.insertBefore(eventInfo, postTitle.nextSibling);
      }
    } else {
      // Move the event info back to its original location
      if (eventInfo && originalParent) {
        originalParent.insertBefore(eventInfo, originalNextSibling);
      }
    }
  }

  // Store the original location of the event info
  storeOriginalLocation();

  // Listen for resize events
  window.addEventListener('resize', moveEventInfo);

  // Check initially in case the page is already loaded in a small window
  moveEventInfo();
});
/******/ })()
;
//# sourceMappingURL=reorder-page.js.map