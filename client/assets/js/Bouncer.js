import { map, pick, find } from 'lodash'

class Bouncer {
    constructor (user) {
        if (!user) {
            this.id = null
            this.abilities = []
            this.roles = []

            return
        }

        this.id = user.id
        this.roles = map(user.roles, role => pick(role, ['name', 'title']))
        this.abilities = map(user.permissions || [], (ability) => {
            return pick(ability, [
                'id',
                'entity_id',
                'entity_type',
                'name',
                'forbidden',
                'only_owned',
                'title'
            ])
        })
    }

    // Find the abilities that give the user permission to do the ability we are
// checking and if we have one and the ability isn't one that forbids them
// then we return true.
    can (abilityName, entityType = null, entity = null) {
    // Filter abilities to only ones that might be relevant to the given ability name.
        let abilities = this.abilities.filter((ability) => {
            if (abilityName === ability.name || ability.name === '*') {
                if (ability.entity_type === '*') {
                    return true
                }

                // if the ability has only_owned set to true entities to be allowed to be accessed
                // then we need to check that the entity's user_id matches the id of our
                // user
                if (ability.only_owned) {
                    if (entity === null || entityType === null) {
                        return false
                    }

                    if (entityType === ability.entity_type && entity.user_id !== this.id) {
                        return false
                    }
                }

                if (ability.entity_type && entityType !== ability.entity_type) {
                    return false
                }

                if (ability.entity_id) {
                    if (!entity) {
                        return false
                    }

                    if (entity.id !== ability.entity_id) {
                        return false
                    }
                }

                return true
            }

            return false
        })

        // if there are no relevant abilities or some of the relevant abilities are
        // forbidden then return false
        if (abilities.length === 0 || abilities.some(ability => ability.forbidden)) {
            return false
        }

        return true
    }

    // Determine if the user's roles contain any of the roles we are looking for
    isA (roles) {
        roles = typeof roles === 'string' ? Array.from(arguments) : roles

        return roles.some((name) => {
            return find(this.roles, { name })
        })
    }

    cannot (ability, entityType = null, entity = null) {
        return !this.can(ability, entityType, entity)
    }
    
    
    isNotA (roles) {
        return !this.isA(roles)
    }
}

export default Bouncer;