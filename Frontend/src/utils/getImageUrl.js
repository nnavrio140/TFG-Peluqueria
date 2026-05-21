export const getImageUrl = (url, fallback = "/img/default.webp") => {
  if (!url) return fallback;

  return url.replace(
    "http://ec2-16-192-23-37.eu-north-1.compute.amazonaws.com",
    ""
  );
};