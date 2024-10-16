declare module '@inertiaui/modal-vue' {
  import { DefineComponent } from 'vue';

  const Modal: DefineComponent<any, any, any>;
  const ModalLink: DefineComponent<any, any, any>;
  const ModalRoot: DefineComponent<any, any, any>;

  export { Modal, ModalLink, ModalRoot };
}
