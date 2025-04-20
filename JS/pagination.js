const pageNumbers = document.querySelectorAll('.page-number');
const nextButton = document.querySelector('.next-button');

pageNumbers.forEach(page => {
  page.addEventListener('click', () => {
    document.querySelector('.current-page').classList.remove('current-page');
    page.classList.add('current-page');
    const selectedPage = page.getAttribute('data-page');
    console.log(`Page ${selectedPage} selected`);
    // Add logic to load content for the selected page
  });
});

nextButton.addEventListener('click', () => {
  const currentPage = document.querySelector('.current-page');
  const nextPage = currentPage.nextElementSibling;
  if (nextPage && nextPage.classList.contains('page-number')) {
    currentPage.classList.remove('current-page');
    nextPage.classList.add('current-page');
    const selectedPage = nextPage.getAttribute('data-page');
    console.log(`Page ${selectedPage} selected`);
    // Add logic to load content for the next page
  }
});