import {onMounted, onUnmounted, ref} from 'vue';

export default function useClickOutside(elementRef, callback) {
  const listener = event => {
    if (!elementRef.value.contains(event.target)) {
      callback();
    }
  };

  onMounted(() => {
    document.addEventListener('click', listener);
  });

  onUnmounted(() => {
    document.removeEventListener('click', listener);
  });
}

