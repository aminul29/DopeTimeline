const dopeTimelineInit = ($scope) => {
  const container = $scope ? $scope[0] : document;
  const timelineItems = container.querySelectorAll(".timeline-item");

  if (!timelineItems.length) return;

  const observerOptions = {
    threshold: 0.2,
    rootMargin: "0px 0px -50px 0px",
  };

  const observer = new IntersectionObserver((entries, observer) => {
    entries.forEach((entry) => {
      if (entry.isIntersecting) {
        entry.target.classList.add("visible");
        // Once it's visible, we don't need to observe it anymore
        observer.unobserve(entry.target);
      }
    });
  }, observerOptions);

  timelineItems.forEach((item) => {
    observer.observe(item);
  });
};

document.addEventListener("DOMContentLoaded", () => {
  dopeTimelineInit();
});

// Run when Elementor loads the widget in the editor
window.addEventListener('elementor/frontend/init', () => {
  if (typeof elementorFrontend !== 'undefined') {
    elementorFrontend.hooks.addAction('frontend/element_ready/dope_timeline.default', ($scope) => {
      dopeTimelineInit($scope);
    });
  }
});
