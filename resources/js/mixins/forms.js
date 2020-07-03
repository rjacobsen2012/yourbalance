import Vue from 'vue'
import { Errors } from 'form-backend-validation'
import VSelect from 'vue-select'
import Warn from '@mixins/warn'
import User from '@mixins/user'

export const Delete = {
    name: 'Delete',
    mixins: [ Warn ],
    methods: {
        maybeDelete(...args) {
            this.$toasted.show('Are you sure you want to delete?', {
                duration: null,
                action: [{
                    text: 'Cancel',
                    href: 'javascript:void(0)',
                    onClick: (e, toastObject) => toastObject.goAway(0),
                }, {
                    text: 'Yes',
                    href: 'javascript:void(0)',
                    onClick: (e, toastObject) => {
                        toastObject.goAway(0)
                        this.delete(...args)
                    },
                    class: 'btn-danger'
                }],
            })
        },

        delete() {
            this.undefinedMethodWarning('delete')
        },
    }
}

/**
 * @TODO turbolinks:before-visit doesn't have an analogue yet in inertia.js,
 *       and you can't hook into pushState without unsafe hacks, so that
 *       functionality doesn't work. Update that when those events come to
 *       inertia.js.
 */
export const CreateEdit = {
    mixins: [ Warn, User ],

    components: { VSelect },

    data() {
        return {
            form: null,
            saving: false,
            errors: new Errors(),
            api: null,
        }
    },

    computed: {
        hasChanges() {
            return Boolean(_.keys(this.fields).find(key => this.fields[key].changed))
        },

        isCreate() {
            return typeof this[this.model] === 'undefined'
        },

        modelObject() {
            return this.isCreate ? null : this[this.model]
        },
    },

    created() {
        if (!this.attributes) {
            this.undefinedAttrWarning('attributes')
        }

        if (!this.model) {
            this.undefinedAttrWarning('model')
        }

        // document.addEventListener('turbolinks:before-visit', this.beforeRouteLeave)

        this.setInitialValues()
    },

    mounted() {
        if (!this.api) {
            this.undefinedAttrWarning('api')
        }
    },

    destroyed() {
        // document.removeEventListener('turbolinks:before-visit', this.beforeRouteLeave)
    },

    methods: {
        onReset() {
            this.setInitialValues()
        },

        setInitialValues() {
            if (this.modelObject) {
                this.form = _.pick(this.modelObject, this.attributes)
            } else {
                this.form = _.tap({}, form => this.attributes.forEach(attr => {
                    form[attr] = attr === 'enabled' ? 1 : null
                }))
            }
        },

        maybeSave() {
            if (this.hasChanges) {
                this.save()
            } else {
                this.$toasted.show('Nothing to update!')
            }
        },

        save() {
            this.saving = true
            this.request()
                .then(data => {
                    this.$validator.reset()
                    this.handleSaveResponse(data)
                })
                .catch(({ response: { data: { errors }} }) => {
                    this.errors = new Errors(errors)
                    this.saving = false
                })
        },

        request() {
            return this.axios.request({
                method: 'post',
                url: this.api + (!this.isCreate ? `/${this.modelObject.id}` : ''),
                data: this.resourceFormData(),
                headers: { 'content-type': 'multipart/form-data' },
            })
        },

        resourceFormData() {
            return _.tap(new FormData(), formData => {
                _.keys(this.form).map(key => {
                    if (this.isCreate || (this.fields[key] && this.fields[key].changed)) {
                        formData.set(key, this.form[key] ? this.form[key] : '')
                    }
                })

                formData.set('user_id', this.$user.id)

                if (!this.isCreate) {
                    formData.set('_method', 'PUT')
                }
            })
        },

        beforeRouteLeave(event) {
            if (!this.hasChanges) {
                return
            }

            event.preventDefault()

            this.$toasted.show('You have unsaved changes. Are you sure you want to continue?', {
                duration: null,
                action: [{
                    text: 'Cancel',
                    href: 'javascript:void(0)',
                    onClick: (e, toastObject) => toastObject.goAway(0),
                }, {
                    text: 'Yes',
                    href: 'javascript:void(0)',
                    onClick: () => {
                        // document.removeEventListener('turbolinks:before-visit', this.beforeRouteLeave)
                        this.$inertia.visit(event.data.url)
                    },
                }],
            })
        },

        handleSaveResponse() {
            this.undefinedMethodWarning('handleSaveResponse')
        },
    },
}
