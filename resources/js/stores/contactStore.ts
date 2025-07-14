import { useLocalStorage } from '@/composables/useLocalStorage'
import { Customer } from '@/types'
import axios from 'axios'
import { defineStore } from 'pinia'
import { ref } from 'vue'

export const useContactStore = defineStore('contacts', () => {

  const selectedContacts = ref<(string|null)[]>([])

  const contacts = useLocalStorage<Customer[] | []>('active_contact')

  async function fetchContacts(filter: string = ''): Promise<void> {

    const resp = await axios.get(filter.length ? `/${filter}` : '/');

    contacts.value = resp.data;

  }

  async function storeContact(contact: Customer): Promise<void> {

    await axios.post(route('contacts.store'), contact)
      .catch(err => {

        console.log(err);

      })
      .then(async (resp) => {

        console.log(resp?.data);

        // await fetchContacts();

      });

  }

  async function updateContact(contact: Customer): Promise<void> {

    await axios.put(route('contacts.update', { contact: contact.cid }))
    .catch(err => console.log(err))
    .then(async (resp) => {

      console.log(resp?.data);

      // await fetchContacts();

    });

  }

  function selectContact(contactId: string) {

    if ( ! contactId.length ) {

      console.log('Yalakwa');

    }

    selectedContacts.value.push(contactId)

  }

  function unselectContact(contact: string) {

    selectedContacts.value = (selectedContacts.value.filter(activeId => activeId !== contact))

  }

  function resetSelectedContacts() {

    selectedContacts.value = []

  }

  return {
    contacts,
    selectedContacts,
    selectContact,
    unselectContact,
    updateContact,
    storeContact,
    fetchContacts,
    resetSelectedContacts
  }

})
