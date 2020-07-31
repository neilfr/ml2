export default class Flash {
  constructor(flash) {
    this.data = flash;
    this.active = this.exists();
    this.message = this.get("message", "");
    this.error = this.get("isError", false);
  }

  has(field) {
    return this.exists() && this.data.hasOwnProperty(field);
  }

  get(field, defaultValue = null) {
    if (this.has(field)) {
      return this.data[field];
    }
    return defaultValue;
  }

  exists() {
    return this.data !== null;
  }

  clear() {
    this.data = null;
    this.active = false;
    this.message = "";
    this.error = false;
  }
}
