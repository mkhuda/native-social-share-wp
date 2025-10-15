document.addEventListener("DOMContentLoaded", () => {
  console.log("Native Social Share: DOM ready.");

  document.querySelectorAll(".nss-container").forEach((shareContainer) => {
    const primaryShareButton = shareContainer.querySelector(".nss-share-button");

    if (!primaryShareButton) return;

    // Jika browser support native share
    if (navigator.share) {
      console.log("Native Social Share: Web Share API supported.");
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
    }
  });
});
