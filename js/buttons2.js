function highlightButton(floor) {
    var buttons = document.querySelectorAll('button[name="newfloor"]');
    buttons.forEach(function(button) {
        if (button.value == floor) {
            button.classList.add('button-active');
        } else {
            button.classList.remove('button-active');
        }
    });
}

window.onload = function() {
    var selectedFloor = <?php echo isset($_SESSION['selectedFloor']) ? json_encode($_SESSION['selectedFloor']) : 'null'; ?>;
    var highlightButtonFlag = <?php echo isset($_SESSION['highlightButton']) ? json_encode($_SESSION['highlightButton']) : 'false'; ?>;
    if (highlightButtonFlag) {
        highlightButton(selectedFloor);
    }
};
