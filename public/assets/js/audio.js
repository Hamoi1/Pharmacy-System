const url = window.location;
const urlArray = url.href.split('/');
const language = urlArray[3];
const urlhost = url.host;

const enable = document.getElementById('enable');
window.SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;
const recognition = new SpeechRecognition();
recognition.lang = 'en';
recognition.addEventListener("result", (event) => {
    // get all text
    const text = Array.from(event.results)
        .map(result => result[0])
        .map(result => result.transcript)
        .join('');
    checkText(text)

});
recognition.addEventListener('end', () => {
    recognition.start();
})
enable.addEventListener('click', () => {
    console.log('start');
    recognition.start();
})

let checkText = (text) => {
    if (text.includes('go to product') || text.includes('product') || text.includes('go to products')) {
        return window.location = `http://${urlhost}/${language}/products`;
    }
    if (text.includes('search for')) {
        // get data after search
        const search = text.split('search for');
        const searchValue = search[1].trim();
        document.getElementById('search').value = searchValue;
        // set a ?s=value without refresh
        window.history.pushState({}, '', `?s=${searchValue}`);
    }
}
