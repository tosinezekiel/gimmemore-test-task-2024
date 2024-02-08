import { createStore } from "vuex";
import { auth } from "./auth.module";
import { IState } from "../types/application";

const store = createStore<IState>({
  modules: {
    auth,
  },
});

export default store;