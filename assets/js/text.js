document.addEventListener("DOMContentLoaded", function() {
    var textContainers = document.querySelectorAll('.description-text');

    textContainers.forEach(function(textContainer) {
        var originalText = textContainer.textContent;
        var wordsPerLine = 20;
        
        var words = originalText.split(' ');
        var truncatedText = words.slice(0, wordsPerLine).join(' ');
        
        if (words.length > wordsPerLine) {
            truncatedText += '...';
        }
        
        textContainer.textContent = truncatedText;
    });
});
