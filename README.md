# www.agid.gov.it

Main repository for AgID website.

## How to deploy?

### Prerequisites

* Ansible: `apt-get install ansible` or `brew install ansible`
* Ansistrano: `ansible-galaxy install carlosbuenosvinos.ansistrano-deploy carlosbuenosvinos.ansistrano-rollback`
* Your own SSH-Key added to `your_host.your_domain.it` as user `my_user`

### Procedure


`git clone https://github.com/AgID/www.agid.gov.it`
`cd deploy/stage`

```
# configure ansistrano
# - ssh user
# - target host address
# - web root (ie. /home/agid/sites/www.agid.gov.it)
./configure.sh
```

Configure creates deploy/stage/deploy_*yaml
edit deploy/stage/deploy_*yaml

`make deploy-stage`

### How is done

* The deploy process *doesn't execute migrations*
* The deploy process executes a `drush cim`
* The deploy process deploys the "`master`" branch. Then you have to merge on this branch to see something different :smiley:
