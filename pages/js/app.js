// AgroJá — pequenas interacções (envio de chat, modais de sucesso).
// A navegação entre ecrãs é feita inteiramente por links <a href> normais — sem router em JavaScript.

function closeModal(id) {
  document.getElementById(id).classList.add('hidden');
}
function openModal(id) {
  const overlay = document.getElementById(id);
  overlay.classList.remove('hidden');
  const modal = overlay.querySelector('div');
  setTimeout(() => { modal.classList.remove('scale-90'); modal.classList.add('scale-100'); }, 10);
}
function submitJob(e) {
  e.preventDefault();
  openModal('success-overlay-publish');
  return false;
}

document.addEventListener('DOMContentLoaded', () => {
  const acceptBtn = document.getElementById('btn-aceitar');
  if (acceptBtn) acceptBtn.addEventListener('click', () => openModal('success-overlay-job'));

  const sendBtn = document.getElementById('sendBtn');
  const input = document.getElementById('msgInput');
  function send() {
    if (!input || input.value.trim() === '') return;
    const area = document.getElementById('message-area');
    const time = new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
    const newMsg = document.createElement('div');
    newMsg.className = 'flex flex-col items-end max-w-[85%] self-end';
    newMsg.innerHTML = '<div class="bg-primary-container text-on-primary-container p-4 rounded-t-xl rounded-bl-xl shadow-sm"><p class="font-body-md text-body-md"></p></div><div class="flex items-center gap-1 mt-1 mr-1"><span class="font-label-md text-label-md text-on-surface-variant">' + time + '</span><span class="material-symbols-outlined text-[16px] text-on-surface-variant">done</span></div>';
    newMsg.querySelector('p').textContent = input.value;
    area.appendChild(newMsg);
    input.value = '';
    area.scrollTo({ top: area.scrollHeight, behavior: 'smooth' });
  }
  if (sendBtn) sendBtn.addEventListener('click', send);
  if (input) input.addEventListener('keypress', (e) => { if (e.key === 'Enter') send(); });
});
