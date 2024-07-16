/******/ (() => { // webpackBootstrap
var __webpack_exports__ = {};
/*!*******************************************!*\
  !*** ./src/blocks/events/event-search.js ***!
  \*******************************************/
document.addEventListener('DOMContentLoaded', function () {
  // Toggle for search form
  const searchToggle = document.getElementById('search-toggle');
  searchToggle.addEventListener('click', function () {
    this.classList.toggle('open');
  });

  // Field filled out background
  const formElements = document.querySelectorAll('#event-search-form select, #event-search-form input[type="text"]');
  formElements.forEach(element => {
    checkValue(element);
    element.addEventListener('change', function () {
      checkValue(this);
    });
    if (element.type === "text") {
      element.addEventListener('input', function () {
        checkValue(this);
      });
    }
  });
  function checkValue(element) {
    if (element.value !== '') {
      element.classList.add('filled');
    } else {
      element.classList.remove('filled');
    }
  }

  // Form functions
  const form = document.getElementById('event-search-form');
  const clearAllButton = document.querySelector('button[type="reset"]');
  form.addEventListener('submit', function (e) {
    e.preventDefault();
    submitForm();
  });
  clearAllButton.addEventListener('click', function (e) {
    e.preventDefault();
    resetFormAndUrl();
  });
  document.querySelectorAll('.clear-field').forEach(function (clearButton) {
    clearButton.addEventListener('click', function () {
      const inputWrapper = this.closest('.input-wrapper');
      const input = inputWrapper.querySelector('input, select');
      if (input) {
        if (input.tagName.toLowerCase() === 'input') {
          input.value = '';
        } else if (input.tagName.toLowerCase() === 'select') {
          input.selectedIndex = 0;
        }
        submitForm();
      }
    });
  });
  function submitForm() {
    const formData = new FormData(form);
    const searchData = {};
    for (const pair of formData.entries()) {
      if (pair[1].trim() !== '') {
        searchData[pair[0]] = pair[1].trim();
      }
    }
    const queryString = new URLSearchParams(searchData).toString();
    const baseUrl = window.location.protocol + "//" + window.location.host + window.location.pathname;
    window.location.href = baseUrl + (queryString ? '?' + queryString : '');
  }
  function resetFormAndUrl() {
    form.reset();
    const baseUrl = window.location.protocol + "//" + window.location.host + window.location.pathname;
    window.location.href = baseUrl;
  }
});
/******/ })()
;
//# sourceMappingURL=event-search.js.map