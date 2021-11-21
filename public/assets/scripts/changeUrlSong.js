const playButtons = document.getElementsByClassName('play-button');
const videoPlayer = document.getElementById('video-player');
for (const playButton of playButtons) {
        playButton.addEventListener('click',function(){
            console.log(playButton.dataset.url)
            videoPlayer.src = 'https://www.youtube.com/embed/' + playButton.dataset.url + '?autoplay=1';
    })
}