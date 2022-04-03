#! /bin/bash
# A modification of Dean Clatworthy's deploy script as found here: https://github.com/deanc/wordpress-plugin-git-svn
# The difference is that this script lives in the plugin's git repo & doesn't require an existing SVN repo.

# main config
PLUGINSLUG="dp-excerpt-plus"
CURRENTDIR=`pwd`
MAINFILE="dp-excerpt-plus.php" # this should be the name of your main php file in the wordpress plugin

# git config
GITPATH="$CURRENTDIR" # this file should be in the base of your git repository

# svn config
SVNPATH="./svn" # path to a temp SVN repo. No trailing slash required and don't add trunk.
SVNURL="http://plugins.svn.wordpress.org/dp-excerpt-plus/" # Remote SVN repo on wordpress.org, with no trailing slash
SVNUSER="getdp" # your svn username


function say_red {
    echo -e "\033[31m>>> $@\033[0m"
}

function say_green {
    echo -e "\033[32m>>> $@\033[0m"
}

function say_yellow {
    echo -e "\033[33m>>> $@\033[0m"
}

# Let's begin...
say_green "======================================="
say_green "Preparing to deploy WordPress plugin"
say_green "======================================="
echo 

# # Check version in readme.txt is the same as plugin file
VERSION_IN_TXT=`grep "^Stable tag" "$GITPATH/readme.txt" | awk -F':' '{print $2}' | tr -d ' '`
say_yellow "Version in txt: $VERSION_IN_TXT"
VERSION_IN_PHP=`grep "Version:" "$GITPATH/$MAINFILE" | awk -F':' '{print $2}' | tr -d ' '`
say_yellow "Version in php: $VERSION_IN_PHP"

if [ "$VERSION_IN_TXT" != "$VERSION_IN_PHP" ]; 
then say_red "Versions don't match. Exiting...."; 
exit 1; 
fi

say_green "Versions match in readme.txt and PHP file. Let's proceed..."

# cd $GITPATH
# echo -e "Enter a commit message for this new version: \c"
# read COMMITMSG
# git commit -am "$COMMITMSG"

# echo "Tagging new version in git"
# git tag -a "$NEWVERSION1" -m "Tagging version $NEWVERSION1"

# echo "Pushing latest commit to origin, with tags"
# git push origin master
# git push origin master --tags

# echo 
# echo "Creating local copy of SVN repo ..."
# svn co $SVNURL $SVNPATH

# echo "Exporting the HEAD of master from git to the trunk of SVN"
# git checkout-index -a -f --prefix=$SVNPATH/trunk/

# echo "Ignoring github specific & deployment script"
# svn propset svn:ignore "deploy.sh
# README.md
# .git
# .gitignore" "$SVNPATH/trunk/"

echo "Copying .wordpress-org to svn/assets"
cp ./.wordpress-org/* ./svn/assets
svn add $SVNPATH/assets/

# echo "Changing directory to SVN"
# cd $SVNPATH/trunk/
# # Add all new files that are not set to be ignored
# svn status | grep -v "^.[ \t]*\..*" | grep "^?" | awk '{print $2}' | xargs svn add
# echo "committing to trunk"
# svn commit --username=$SVNUSER -m "$COMMITMSG"

# echo "Updating WP plugin repo assets & committing"
# cd $SVNPATH/assets/
# svn commit --username=$SVNUSER -m "Updating wp-repo-assets"

# echo "Creating new SVN tag & committing it"
# cd $SVNPATH
# svn copy trunk/ tags/$NEWVERSION1/
# cd $SVNPATH/tags/$NEWVERSION1
# svn commit --username=$SVNUSER -m "Tagging version $NEWVERSION1"

# echo "Removing temporary directory $SVNPATH"
# rm -fr $SVNPATH/

# echo "*** FIN ***"