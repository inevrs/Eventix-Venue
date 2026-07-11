function togglePass(targetId = 'passInput') {
    const input = document.getElementById(targetId);
    const button = document.querySelector(`[data-password-toggle="${targetId}"]`);
    if (!input || !button) return;

    const isHidden = input.type === 'password';
    input.type = isHidden ? 'text' : 'password';

    button.innerHTML = isHidden
        ? '<svg viewBox="0 0 24 24" class="w-4 h-4 fill-current"><path d="M2 12s3.5-6 10-6 10 6 10 6-3.5 6-10 6S2 12 2 12Zm10 3a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z"/></svg>'
        : '<svg viewBox="0 0 24 24" class="w-4 h-4 fill-current"><path d="M3 3l18 18M10.6 10.6A3 3 0 0 0 13.4 13.4M9.1 5.2A11.1 11.1 0 0 1 12 4c6.5 0 10 8 10 8a19.2 19.2 0 0 1-4.3 5.2M6.5 6.5A19.3 19.3 0 0 0 2 12s3.5 8 10 8a11.6 11.6 0 0 0 3.5-.5"/></svg>';
}