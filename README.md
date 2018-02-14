# www.agid.gov.it

Main repository for AgID website.

## How to deploy?

### Prerequisites

* Ansible: `apt-get install ansible` or `brew install ansible`
* Ansistrano: `ansible-galaxy install carlosbuenosvinos.ansistrano-deploy carlosbuenosvinos.ansistrano-rollback`
* Your own SSH-Key added to `your_host.your_domain.it` as user `my_user`

### Procedure

`make deploy-prod`

### How is done

* The deploy process *doesn't execute migrations*
* The deploy process executes a `drush cim`
* The deploy process deploys the "`master`" branch. Then you have to merge on this branch to see something different :smiley:
