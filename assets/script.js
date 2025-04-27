document.addEventListener("DOMContentLoaded", () => {
  let audios = document.getElementsByTagName("audio");
  audios = Array.from(audios);

  for (const audio of audios) {
    audio.addEventListener("play", (event) => {
      audios
        .filter((audio) => audio !== event.target && !audio.paused)
        .forEach((audio) => audio.pause());
    });

    audio.addEventListener("ended", (event) => {
      audios[audios.indexOf(event.target) + 1]?.play();
    });

    audio.addEventListener("volumechange", (event) => {
      audios
        .filter((audio) => audio !== event.target)
        .forEach((audio) => (audio.volume = event.target.volume));
    });
  }
});
