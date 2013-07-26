/*
 * ============================================================================
 *
 *       Filename:  search.cc
 *
 *    Description:  
 *
 *        Version:  1.0
 *        Created:  07/25/13 10:36:56
 *       Revision:  none
 *       Compiler:  gcc
 *
 *         Author:  lxf (), 
 *        Company:  NDSL
 *
 * ============================================================================
 */
#include    <iostream>
using namespace std;

int main(int argc, char *argv[])
{
    cerr << "form cerr";
    cout << "Hello php, i am c++" << endl;
    for (int i = 0; i != argc; i++)
    {
        cout << i << "argument:" << argv[i] << endl;
    }

    return 0;
}
