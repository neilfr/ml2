export default class Errors {
  constructor(errors) {
    this.data = errors;
  }

  any() {
    return Object.keys(this.data).length > 0;
  }

  none() {
    return !this.any();
  }

  has(field) {
    return this.data.hasOwnProperty(field);
  }

  get(field) {
    if (this.has(field)) {
      return this.data[field];
    }
    return null;
  }

  first(field) {
    let result = this.get(field);
    if (result && Array.isArray(result)) {
      return result[0];
    }
    return result;
  }

  clear(field) {
    if (this.has(field)) {
      delete this.data[field];
    }
  }

  clearAll() {
    this.data = {};
  }
}
