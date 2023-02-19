import { createI18n } from "vue-i18n";
import langVi from "../locales/vi.json";
const i18n = createI18n({
  legacy: false,
  locale: "vi",
  messages: {
    vi: langVi,
  },
});
export default i18n;
