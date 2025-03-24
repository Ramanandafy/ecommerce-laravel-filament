import './bootstrap';
import 'preline';
document.addEventListener('livewire:navicated', ()=>{
    window.HSStaticMethods.autoInit();
})
import Swal from 'sweetalert2';
window.Swal = Swal
