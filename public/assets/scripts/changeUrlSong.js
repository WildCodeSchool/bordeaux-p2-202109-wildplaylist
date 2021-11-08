
const playButtons = document.getElementsByClassName('play-button');
//const videoPlayer = document.getElementById('video-player');
const playerContainer = document.getElementById('player-container');
for (const playButton of playButtons) {
    playButton.addEventListener('click',function(){
        const videoPlayer = document.createElement('iframe');
        const url = playButton.dataset.url;
        let embed = url.split("=")[1];
        videoPlayer.src = "https://www.youtube.com/embed/" + embed;
        videoPlayer.className="shadow-1-strong rounded";
        videoPlayer.setAttribute('title','youtube video');
        videoPlayer.setAttribute('allowfullscreen','true');
        playerContainer.appendChild(videoPlayer);
    })
}


console.log(playButtons);