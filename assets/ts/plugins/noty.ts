import { App } from 'vue';
import Noty from 'noty';
import 'noty/lib/noty.css'; // Import Noty CSS
import 'noty/lib/themes/mint.css'; // Import a theme

export default {
  install: (app: App) => {
    app.config.globalProperties.$noty = Noty;
  }
};