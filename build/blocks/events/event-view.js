/******/ (() => { // webpackBootstrap
var __webpack_exports__ = {};
/*!*****************************************!*\
  !*** ./src/blocks/events/event-view.js ***!
  \*****************************************/
document.addEventListener('DOMContentLoaded', () => {
  const gridViewButton = document.querySelector('.grid-view');
  const listViewButton = document.querySelector('.list-view');
  const postsContainer = document.querySelector('.wp-block-therosehill-plus-events .event-listings');
  const localStorageKey = `eventViewMode_${window.location.pathname}`;
  function toggleView(activeView) {
    if (activeView === 'grid') {
      postsContainer.classList.add('grid-view-active');
      postsContainer.classList.remove('list-view-active');
      gridViewButton.classList.add('active');
      listViewButton.classList.remove('active');
    } else if (activeView === 'list') {
      postsContainer.classList.add('list-view-active');
      postsContainer.classList.remove('grid-view-active');
      listViewButton.classList.add('active');
      gridViewButton.classList.remove('active');
    }
    localStorage.setItem(localStorageKey, activeView);
  }
  gridViewButton.addEventListener('click', () => toggleView('grid'));
  listViewButton.addEventListener('click', () => toggleView('list'));
  const savedViewMode = localStorage.getItem(localStorageKey);
  const defaultView = savedViewMode || (postsContainer.classList.contains('grid-view-active') ? 'grid' : 'list');
  toggleView(defaultView);
});
/******/ })()
;
//# sourceMappingURL=event-view.js.map