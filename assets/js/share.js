document.addEventListener("DOMContentLoaded", () => {
  document.querySelectorAll(".nss-container").forEach((shareContainer) => {
    const primaryShareButton = shareContainer.querySelector(".nss-share-button");

    if (!primaryShareButton) return;

    if (navigator.share) {
      primaryShareButton.addEventListener("click", async (event) => {
        event.preventDefault();
        const title = shareContainer.dataset.shareTitle || document.title;
        const url = shareContainer.dataset.shareUrl || window.location.href;
        const metaDescription = document.querySelector('meta[name="description"]');
        const text = metaDescription ? metaDescription.content : "Check this out!";

        try {
          await navigator.share({ title, text, url });
        } catch (err) {
          console.info("Share canceled or failed:", err);
        }
      });
    } else {
      console.warn("Web Share API not supported. Falling back to traditional share links.");
    }
  });
});
