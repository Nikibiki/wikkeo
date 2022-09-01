import Service from "./Service";

export default class SellersService extends Service {
    getUrl() {
        return this.getPrefix() + 'sellers';
    }
    async getAll() {
        const url = this.getUrl();
        return (await axios.get(url)).data;
    }

    async getItem(id) {
        const url = this.getUrl();
        return (await axios.get(`${url}/${id}`)).data;
    }

    update(data) {

    }

    save(data, isNew = true) {
        const url = this.getUrl();
        if (!isNew) {
            data._method = 'PATCH';
        }
        return axios.post(`${url}/${isNew ? '' : 'edit'}`, data);
    }

    destroy(id) {
        const url = this.getUrl();
        return axios.post(`${url}/${id}/`, {
            _method: 'DELETE'
        });
    }
}
