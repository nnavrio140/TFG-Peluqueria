export const getImageUrl = (
  url,
  fallback = "/img/default.webp",
  storageFolder = ""
) => {
  if (!url) return fallback;

  const backendUrl = "http://ec2-16-192-23-37.eu-north-1.compute.amazonaws.com";

  if (url.startsWith(backendUrl)) {
    return url.replace(backendUrl, "");
  }

  if (url.startsWith("/storage/")) {
    return url;
  }

  if (url.startsWith("/img/")) {
    return url;
  }

  if (url.startsWith("http://") || url.startsWith("https://")) {
    return url;
  }

  if (storageFolder) {
    return `${storageFolder}/${url}`;
  }

  return url;
};