let lastScroll = 0;
    const header = document.getElementById('header');
    window.addEventListener('scroll', () => {
      const current = window.pageYOffset;
      if (current > lastScroll) {
        header.classList.add('hide');
      } else {
        header.classList.remove('hide');
      }
      lastScroll = current;
    });

const modal = document.getElementById("profileModal");
const openButtons = document.querySelectorAll("#openProfileModal, #openProfileModalLink");
const closeBtn = document.querySelector(".modal .close");

if (openButtons) {
    openButtons.forEach(btn => {
        btn.addEventListener("click", e => {
            e.preventDefault();
            modal.style.display = "block";
        });
    });
}

if (closeBtn) {
    closeBtn.addEventListener("click", () => {
        modal.style.display = "none";
    });
}

window.addEventListener("click", e => {
    if (e.target === modal) modal.style.display = "none";
});

