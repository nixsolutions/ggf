#!/bin/bash
# -w - workspace directory on build server
# -u - deploy user on remote server
# -s - server host to deploy to
# -e - app environment
# -c - current commit

while getopts w:u:s:e:c: flag; do
    case $flag in
        w)
            workspace=$OPTARG;
            ;;
        u)
            user=$OPTARG;
            ;;
        s)
            host="$OPTARG";
            ;;
        e)
            APP_ENV="$OPTARG";
            ;;
        c)
            currentCommit=$OPTARG;
            ;;
        ?)
            exit;
            ;;
    esac
done

deployDir="/home/dev/src/ggf_${APP_ENV}"

echo "DEPLOY DIR ======>  $deployDir   "
echo "HOST       ======>  $host       "

echo -e "\tSyncing data in $deployDir with git tag $currentCommit"

mkdir -p $deployDir

echo  " DEFAULT DEPLOY "
rsync -aP --no-o --no-g --delete --progress $workspace/ $deployDir \
--exclude /.buildpacks \
--exclude /.gitattributes \
--exclude /.gitignore \
--exclude /.travis.yml \
--exclude /after.sh \
--exclude /Procfile \
--exclude /readme.md \
--exclude /Homestead.yaml \
--exclude /server.php \
--exclude /Vagrantfile \
--exclude /vendor/ \
--exclude /.vagrant/ \
--exclude /node_modules/ \
--exclude /resources/frontend/node_modules/ \

echo -e "\tRunning install script/smoke tests"
cd ${deployDir} ; \
chmod -v +x $deployDir/bin/post-install.sh ${host} ; \
$deployDir/bin/post-install.sh -e ${APP_ENV} ;