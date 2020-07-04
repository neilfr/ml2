export default class Form {
  constructor(data) {
    this.originalData = JSON.parse(JSON.stringify(data));

    Object.assign(this, data);
  }

  data() {
    return Object.keys(this.originalData).reduce((data, attribute) => {
      data[attribute] = this[attribute];

      return data;
    }, {});
  }

  reset(fields = []) {
    if (Array.isArray(fields) && fields.length > 0) {
      fields.forEach(field => {
        if (this.originalData.hasOwnProperty(field)) {
          this[field] = this.originalData[field];
        }
      });
      return;
    }

    Object.assign(this, this.originalData);
  }
}
