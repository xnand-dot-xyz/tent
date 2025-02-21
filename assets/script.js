document.addEventListener("DOMContentLoaded", () => {
  let audios = document.getElementsByTagName("audio");
  audios = Array.from(audios);

  for (const audio of audios) {
    audio.addEventListener("play", (event) => {
      audios
        .filter((audio) => event.target !== audio && !audio.paused)
        .forEach((audio) => audio.pause());
    });

    audio.addEventListener("ended", (event) => {
      audios[audios.indexOf(event.target) + 1]?.play();
    });
  }
});
