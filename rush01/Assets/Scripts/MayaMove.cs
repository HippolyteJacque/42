using System.Collections;
using System.Collections.Generic;
using UnityEngine;
using UnityEngine.AI;
using UnityEngine.UI;

public class MayaMove : MonoBehaviour {
    public static MayaMove instance;

	public CanvasGroup itemToolTipCanvas;
	public Text ToolTipContent;

    [SerializeField] Transform inventaire;
    [System.NonSerialized]public int SpellPoints;
    [System.NonSerialized]public int Credits;
    [System.NonSerialized]public int Points;
	[System.NonSerialized]public float HP;
    [System.NonSerialized]public float Mana;
	[System.NonSerialized]public float maxHP;
	[System.NonSerialized]public float STR;
	[System.NonSerialized]public float AGI;
	[System.NonSerialized]public float CON;
	[System.NonSerialized]public float Armor;
	public float minDamage;
	public float maxDamage;

	public float fireRate = 0.75f;

	[System.NonSerialized]public int Level;
	[System.NonSerialized]public float XP;
	[System.NonSerialized]public float xpNextLvl;
	[System.NonSerialized]public float money;

	public Slider HPbar;
	public Slider XPbar;
    public Slider Manabar;
	public Text HPtext;
	public Text XPtext;
	public Text LVLtext;
	public Slider enemyHPbar;
	public Text enemyName;
	public Text enemyLvl;

    public GameObject lvlup;

	private float attackDistance = 2f;

    private bool alive;
	private float nextFire;
	private GameObject target;
	private GameObject targetUI;
	public CanvasGroup EnemyCanvas;
    public CanvasGroup StatsCanvas;
    public CanvasGroup SpellsCanvas;
    public CanvasGroup pointsBtn;
    public CanvasGroup SpellPointsBtn;
	Animator animator;

	public NavMeshAgent agent;
    float time;
	int layerMask = 1 << 2;

    public AudioClip attacking;
    public AudioClip dying;
    public AudioClip lvlingUp;
    public AudioSource audioSource;

    void Awake()
    {
        if (instance != null)
            Destroy(instance.gameObject);
        instance = this;
    }
    void Start() {
        time = Time.timeSinceLevelLoad;
        alive = true;
        Credits = 5;
        SpellPoints = 0;
        Points = 0;
    	STR = 20;
    	AGI = 20;
    	CON = 10;
    	Armor = 15;
    	minDamage = STR/2;
    	maxDamage = minDamage + 4;
		HP = 5 * CON;
		maxHP = HP;
        Mana = maxHP;
		Level = 1;
		XP = 0;
		xpNextLvl = 100;
		money = 0;

    	layerMask = ~layerMask;
        agent = GetComponent<NavMeshAgent>();
        animator = GetComponent<Animator>();

        UImanager();
    }
    
    void Update() {
        if (alive == true){
        	if (Input.GetKeyDown("l")){
                LevelUp();
            }
            recoverMana();
            UImanager();
            if (Input.GetMouseButtonUp(0)) {
                target = null;
                agent.enabled = true;
            }

            if (!agent.pathPending && agent.enabled == true){
                if (agent.remainingDistance <= agent.stoppingDistance){
                    if (!agent.hasPath || agent.velocity.sqrMagnitude == 0f){
                        animator.SetBool("isWalking", false);
                    }
                }
            }

            if (target){
                targetUI = target;
            }
            else {
                Ray ray;
                RaycastHit hit;
                ray = Camera.main.ScreenPointToRay(Input.mousePosition);
                if(Physics.Raycast(ray, out hit)){
                    if (hit.collider.tag == "enemy"){
                        targetUI = hit.transform.gameObject;
                    }
                    else {
                        targetUI = null;
                    }
                }
                else {
                    targetUI = null;
                }
            }
            if (HP <= 0){
                animator.SetBool("isWalking", false);
                animator.SetBool("isFighting", false);
                animator.SetBool("alive", false);
                StartCoroutine("death");
            }
        }
    }

    void FixedUpdate(){
        if (alive == true){
            move();
        }
    }

    void OnTriggerEnter(Collider col){
        if (col.tag == "LifeBall"){
            if (HP + maxHP * 0.3f > maxHP)
                HP = maxHP;
            else
                HP += maxHP * 0.3f;
            Destroy(col.gameObject);
        }
    }

    void OnTriggerStay(Collider col){
        if (col.tag == "item" && Input.GetKeyDown(KeyCode.E)){
            bool haveSpace = false;
            Transform targetSlot = null;
            foreach (Transform child in inventaire)
            {
                if (child.childCount == 0)
                {
                    targetSlot = child;
                    haveSpace = true;
                }
            }
            if (!haveSpace)
                return;

            GameObject tmp = Instantiate(SpritePool.instance.itemInUi, targetSlot.transform.position, Quaternion.identity);

            ItemStats t = col.gameObject.GetComponent<ItemStats>();
            ItemStats t2 = tmp.GetComponent<ItemStats>();
            t2.damage = t.damage;
            t2.attackSpeed = t.attackSpeed;
            t2.type = t.type;
            tmp.GetComponent<Image>().sprite = col.GetComponent<SpriteRenderer>().sprite;
            tmp.transform.SetParent(targetSlot);
            tmp.transform.localPosition = Vector3.zero;
            Destroy(col.gameObject);
        }
    }

    void UImanager(){
    	HPbar.value = HP/maxHP;
        Manabar.value = Mana/maxHP;
    	XPbar.value = XP/xpNextLvl;
    	if (HP >= 0)
    		HPtext.text = Mathf.RoundToInt(HP).ToString() + "/" + maxHP.ToString();
    	else
    		HPtext.text = "0/" + maxHP.ToString();
    	XPtext.text = Mathf.RoundToInt(XP).ToString() + "/" + xpNextLvl.ToString();
    	LVLtext.text = Level.ToString();

    	if (targetUI){
    		EnemyCanvas.alpha = 1f;

            if (targetUI.GetComponent<ZombieMove>() != null)
            {
                enemyLvl.text = targetUI.GetComponent<ZombieMove>().Level.ToString();
                enemyHPbar.value = targetUI.GetComponent<ZombieMove>().HP/targetUI.GetComponent<ZombieMove>().maxHP;
            }
			enemyName.text = targetUI.name.Replace("(Clone)", "");
    	}
    	else {
    		EnemyCanvas.alpha = 0f;
    	}
        if (Points > 0)
            pointsBtn.alpha = 1f;
        else
            pointsBtn.alpha = 0f;
        if (SpellPoints > 0)
            SpellPointsBtn.alpha = 1f;
        else
            SpellPointsBtn.alpha = 0f;
    }

    void recoverMana(){
        float cd = 1f;
        if (time <= Time.timeSinceLevelLoad)
        {
            if (Mana < maxHP){
                time += cd;
                Mana += maxHP * 0.05f;
                if (Mana > maxHP)
                    Mana = maxHP;
            }
        }
    }

    void move(){
    	if (Input.GetMouseButton(0)) {
            RaycastHit hit;
            Vector3 mouse = Camera.main.ScreenToViewportPoint(Input.mousePosition);
            if (Physics.Raycast(Camera.main.ScreenPointToRay(Input.mousePosition), out hit, 100, layerMask)) {

                // if (StatsCanvas == 1f && mouse.x <= 0.5 || SpellsCanvas.alpha == 1f && mouse.x >= 0.5){
                //     ;
                // }

                if (hit.collider.tag == "enemy" && Vector3.Distance(transform.position, hit.point) <= attackDistance && target == null){
                    target = hit.transform.gameObject;
                    animator.SetBool("isWalking", false);
                    attack(target);
                }
                else if (hit.collider.tag == "enemy" && Vector3.Distance(transform.position, hit.point) > attackDistance && target == null){
                    agent.destination = hit.point;
                    target = hit.collider.gameObject;
                    animator.SetBool("isWalking", true);
                }
                else if (target == null){
                    transform.LookAt(hit.point);
                    agent.destination = hit.point;
                    animator.SetBool("isFighting", false);
                    animator.SetBool("isWalking", true);
                }
            }
        }
        if (target){
        	transform.LookAt(target.transform.position);
        	if (Vector3.Distance(transform.position, target.transform.position) <= attackDistance){
        		animator.SetBool("isWalking", false);
        		attack(target);
        	}
        	else {
                agent.enabled = true;
        		animator.SetBool("isFighting", false);
        		animator.SetBool("isWalking", true);
        		agent.destination = target.transform.position;
        	}
        }
    }

    void attack(GameObject enemy){
        if (enemy.GetComponent<ZombieMove>() == null)
            return;
    	agent.enabled = false;
 
    	if (Time.time > nextFire && enemy.GetComponent<ZombieMove>().HP > 0){
    		StopCoroutine("dmg");
			animator.SetBool("isFighting", true);
			nextFire = Time.time + fireRate;
			StartCoroutine(dmg(enemy));
		}
    	if (enemy.GetComponent<ZombieMove>().HP <= 0){
    		animator.SetBool("isFighting", false);
    		agent.enabled = true;
    		target = null;
    	}
    }

    public void LevelUp(float xp = 0f){
        StopCoroutine(LvlUp());
        if (xp + XP >= xpNextLvl){
            XP = xp + XP - xpNextLvl;
        }
        else {
            XP = 0;
        }
        Level++;
        StartCoroutine(LvlUp());
        Points += 5;
        SpellPoints += 1;
        HP = maxHP;
        Mana = maxHP;
        xpNextLvl = xpNextLvl * 1.5f;
    }

    IEnumerator dmg(GameObject enemy){
    	yield return new WaitForSeconds(0.5f);
        audioSource.PlayOneShot(attacking);

        if (enemy.GetComponent<ZombieMove>() != null)
        {
            ZombieMove EnemyS = enemy.GetComponent<ZombieMove>();

            float hitChance = 75 + AGI - EnemyS.AGI;
            if (Random.Range(0f, 100f) <= hitChance){

            float dmg = Random.Range(minDamage, maxDamage);
            dmg = dmg * (1 - EnemyS.Armor/200);


            if (EnemyS.GetComponent<ZombieMove>() != null)
                    EnemyS.TakeDmg(dmg);//takedmg;
            if (EnemyS.GetComponent<BossZombie>() != null)
                    EnemyS.TakeDmg(dmg);//takeDamage;
            }
        }
        else
        {
            BossZombie EnemySs = enemy.GetComponent<BossZombie>();

            float hitChance2 = 75 + AGI - EnemySs.AGI;
            if (Random.Range(0f, 100f) <= hitChance2){

            float dmg = Random.Range(minDamage, maxDamage);
            dmg = dmg * (1 - EnemySs.Armor/200);


            if (EnemySs.GetComponent<ZombieMove>() != null)
                    EnemySs.TakeDmg(dmg);//takedmg;
            if (EnemySs.GetComponent<BossZombie>() != null)
                    EnemySs.TakeDmg(dmg);//takeDamage;
            }
        }

    }

    IEnumerator LvlUp(){
        lvlup.SetActive(true);
        audioSource.PlayOneShot(lvlingUp);
        yield return new WaitForSeconds(5);
        lvlup.SetActive(false);
    }

    IEnumerator death(){
        audioSource.PlayOneShot(dying);
        alive = false;
        Credits--;
    	Destroy(GetComponent<CapsuleCollider>());
    	agent.enabled = false;
    	transform.Translate(-Vector3.up * Time.deltaTime/15);
    	yield return new WaitForSeconds(9);
    	//Destroy(gameObject);
    }

    public void addSTR(){
        STR += 1;
        minDamage += 0.5f;
        maxDamage = minDamage + 4;
        Points--;
    }

    public void addAGI(){
        AGI += 1;
        Points--;
    }

    public void addCON(){
        CON += 1;
        Points--;
        HP += 5;
        maxHP= 5 * CON;
    }

}
