function centeredNotification(message, type = 'error') {
    const customToastBar = document.querySelector('.custom-toast-bar');
    const progress = customToastBar.querySelector('.custom-progress');
    const closeIcon = customToastBar.querySelector('.custom-close');
    const customSuccessIcon = customToastBar.querySelector('.custom-success');
    const customErrorIcon = customToastBar.querySelector('.custom-error');
    customToastBar.style.display = 'block';
    let timer1, timer2;

    // Set the message and type in the custom toast
    const toastContent = customToastBar.querySelector('.custom-message');
    toastContent.querySelector('.custom-text-2').textContent = message;

    // Customize the toast based on the type
    switch (type) {
        case 'error':
            toastContent.querySelector('.custom-text-1').textContent = 'Error';
            customSuccessIcon.style.display = 'none';
            customErrorIcon.style.display = 'flex';
            // Add specific styling or icons for errors if needed
            break;
        case 'success':
            toastContent.querySelector('.custom-text-1').textContent = 'Success';
            customErrorIcon.style.display = 'none';
            customSuccessIcon.style.display = 'flex';
            // Add specific styling or icons for success if needed
            break;
        default:
            toastContent.querySelector('.custom-text-1').textContent = 'Info';
            // Add specific styling or icons for info if needed
            break;
    }

    // Display the custom toast
    customToastBar.classList.add('active');
    progress.classList.add('active');

    timer1 = setTimeout(() => {
        customToastBar.classList.remove('active');
    }, 3000); // 5 seconds

    timer2 = setTimeout(() => {
        progress.classList.remove('active');
    }, 3300);

    // Event listener to close the toast
    closeIcon.addEventListener('click', () => {
        customToastBar.classList.remove('active');

        setTimeout(() => {
            progress.classList.remove('active');
        }, 300);

        clearTimeout(timer1);
        clearTimeout(timer2);
    });
}